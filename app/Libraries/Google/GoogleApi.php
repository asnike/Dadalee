<?php

/**
 * Created by PhpStorm.
 * User: ruudnike
 * Date: 2017-03-28
 * Time: 오후 1:35
 */
namespace App\Libraries\Google;
use Google_Client;

class GoogleApi
{
    private $auth_path;
    private $callback;
    private $scope;
    private $client;

    public function init($auth_path, $callback, $scope){
        $this->auth_path = $auth_path;
        $this->callback = $callback;
        $this->scope = $scope;
        $this->client = $this->getClient();
        return $this;
    }
    public function revoke(){
        return $this->client->revokeToken();
    }
    public function createSpreadSheets($title){
        $service = new \Google_Service_Sheets($this->client);
        $sheets = new \Google_Service_Sheets_Spreadsheet();
        $properties = new \Google_Service_Sheets_SpreadsheetProperties();
        $properties->setTitle($title);
        $sheets->setProperties($properties);

        try{
            $sheets = $service->spreadsheets->create($sheets);
        }catch(\Exception $exception){
            header('Location: '. filter_var($this->callback, FILTER_SANITIZE_URL));
            exit;
        }
        return $sheets;
    }
    private function sheetsBatchUpdate(){
        $service = new \Google_Service_Sheets($this->client);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>$this->requests
            )
        );
        $sheetsId = $this->getSheetsId();
        try{
            $response = $service->spreadsheets->batchUpdate($sheetsId, $batchUpdateRequest);
        }catch(\Google_Service_Exception $exception){
            $code = $exception->getCode();
            switch($code){
                case'401':
                    header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
                    exit;
                default:
                    return $exception->getErrors();

            }
        }

        return $response;
    }
    private function getClient(){
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->auth_path);
        $this->client->addScope($this->scope);

        $access_token = request()->session()->get('access_token');
        if(isset($access_token) && $access_token){
            $this->client->setAccessToken($access_token);
            return $this->client;
        }else{
            header('Location: '. filter_var($this->callback, FILTER_SANITIZE_URL));
            exit;
        }
    }
    public function authCallback(){
        $client = new Google_Client();
        $this->client->setAuthConfig($this->auth_path);
        $this->client->setRedirectUri($this->callback);
        $this->client->addScope($this->scope);

        if(empty(request()->input('code'))){
            $auth_url = $this->client->createAuthUrl();
            header('Location: '. filter_var($auth_url, FILTER_SANITIZE_URL));
        }else{
            $client->authenticate(request()->input('code'));
            request()->session()->set('access_token', $this->client->getAccessToken());
        }
    }
}