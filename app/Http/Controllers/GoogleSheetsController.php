<?php

namespace App\Http\Controllers;


use App\RealEstate;
use GoogleSheet\tests\SheetsTest;
use Illuminate\Http\Request;
use Sheets;
use Google;
use Google_Client;


class GoogleSheetsController extends Controller
{
    //
    public function callback(){

    }
    public function export(Request $request){
        $realestate = RealEstate::with(['earningRate', 'loan'])->findOrFail($request->input('id'));
        $filename = $realestate->building_name.' '.trans('common.earningRateSheet');

        $client = $this->authCheck($request->session()->get('access_token'));
        $sheetId = '1tZPhTDt22Qeh6bT--TVbhj3cKq-jDew1e5GUm4KlCj8';/*$request->input('sheet_id');*/
        $result = $this->setData($client, $sheetId, $realestate);
        return response()->json($result);
    }
    public function import(Request $request){
        $client = $this->authCheck($request->session()->get('access_token'));
        $sheetId = '1tZPhTDt22Qeh6bT--TVbhj3cKq-jDew1e5GUm4KlCj8';/*$request->input('sheet_id');*/
        if(isset($client)){
            $result = $this->getData($client, $sheetId);
            dd($result->values);
        }
    }
    protected function authCheck($access_token){
        $client = new Google_Client();
        $client->setAuthConfig('json/credential.json');
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

        if(isset($access_token) && $access_token){
            $client->setAccessToken($access_token);
            return $client;
        }else{
            header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
        }
    }
    public function authCallback(Request $request){
        $client = new Google_Client();
        $client->setAuthConfig('json/credential.json');
        $client->setRedirectUri(route('auth2.callback'));
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);


        if(empty($request->input('code'))){
            $auth_url = $client->createAuthUrl();
            header('Location: '. filter_var($auth_url, FILTER_SANITIZE_URL));
        }else{
            $client->authenticate($request->input('code'));
            $request->session()->set('access_token', $client->getAccessToken());
            return redirect()->action('GoogleSheetsController@import');
        }
    }
    public function setupCheck(){

    }
    public function setup(){

    }

    protected function getData($client, $sheetId){
        $service = new \Google_Service_Sheets($client);
        $range = 'A1';
        $response = $service->spreadsheets_values->get($sheetId, $range);
        return $response;
    }
    protected function setData($client, $sheetId){
        $service = new \Google_Service_Sheets($client);
        $response = $this->createSpreadSheets($service);

        return $response;

        /*$range = 'A5';
        $body = new \Google_Service_Sheets_ValueRange(array(
            'values' => array(
                array('d3333', 'rewrwerwer', 123123123)
            )
        ));
        $param = array(
            'valueInputOption' => 'RAW'
        );
        $response = $service->spreadsheets_values->append($sheetId, $range, $body, $param);

        $range = 'A1:C10';
        $response = $service->spreadsheets_values->get($sheetId, $range);*/
    }
    protected function createSpreadSheets($service){
        $sheets = new \Google_Service_Sheets_Spreadsheet();
        $properties = new \Google_Service_Sheets_SpreadsheetProperties();
        $properties->setTitle('Dadalee Sheets');
        $sheets->setProperties($properties);
        return $service->spreadsheets->create($sheets);
    }
}
