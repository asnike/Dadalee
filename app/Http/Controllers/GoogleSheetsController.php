<?php

namespace App\Http\Controllers;


use App\RealEstate;
use App\User;
use Illuminate\Http\Request;
use Sheets;
use Google;
use Google_Client;


class GoogleSheetsController extends Controller
{
    //
    protected $requests;
    public function callback(){

    }
    public function export(Request $request){
        $realestate = RealEstate::with(['earningRate', 'loan'])->findOrFail($request->input('id'));
        $user = User::findOrFail(auth()->id());

        $filename = $realestate->building_name.' '.trans('common.earningRateSheet');

        $client = $this->authCheck($request->session()->get('access_token'));
        $sheetId = $user->sheetsId();

        $result = $this->addSheet($client, $filename, TRUE)->addRealEstate($client, $realestate)->batchUpdate();
        return response()->json($result);
    }
    public function import(Request $request){
        $client = $this->authCheck($request->session()->get('access_token'));
        if(isset($client)){
            return $this->addSheet($client, 'teee', TRUE)->batchUpdate($client);
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
            exit;
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
    public function linked(){

    }
    public function link(Request $request){
        $user = User::findOrFail(auth()->id());
        if($user->sheetsId()){

        }else{
            $client = $this->authCheck($request->session()->get('access_token'));
            $result = $this->createSpreadSheets($client);
            $user->update([
                'sheets_id'=>$result->spreadsheetId
            ]);
        }
        return view('mypage.sheetslink');
    }

    protected function getData($client, $sheetId){
        $service = new \Google_Service_Sheets($client);
        $range = 'A1';
        $response = $service->spreadsheets_values->get($sheetId, $range);
        return $response;
    }
    protected function setData($client, $sheetId){


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
    protected function createSpreadSheets($client){
        $service = new \Google_Service_Sheets($client);
        $sheets = new \Google_Service_Sheets_Spreadsheet();
        $properties = new \Google_Service_Sheets_SpreadsheetProperties();
        $properties->setTitle('Dadalee Sheets');
        $sheets->setProperties($properties);

        try{
            $sheets = $service->spreadsheets->create($sheets);
        }catch(\Exception $exception){
            header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
            exit;
        }
        $sheet_properties = new \Google_Service_Sheets_SheetProperties();
        $sheet_properties->setTitle('list');
        $sheets->getSheets()[0]->setProperties($sheet_properties);

        return $sheets;
    }
    protected function addSheet($client, $title, $initRequest = FALSE){
        if($initRequest) $this->requests = array();
        $this->requests[] = new \Google_Service_Sheets_Request(array(
                'addSheet'=>array(
                    'properties'=> array(
                        'title'=>$title
                    )
                )
            )
        );
        return $this;
    }
    protected function addRealEstate($client, $title){
        $user = User::findOrFail(auth()->id());

        $service = new \Google_Service_Sheets_Sheet($client);
        $range = '';
        $optParams = [];
        $optParams['valueInputOption'] = '';
        $requestBody = new \Google_Service_Sheets_ValueRange();
        /*$response = $service->spreadsheets_value->update($user->sheetsId(), $);*/

        return $this;
    }
    protected function batchUpdate($client){

        $service = new \Google_Service_Sheets($client);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>$this->requests
            )
        );

        $user = User::findOrFail(auth()->id());
        try{
            $response = $service->spreadsheets->batchUpdate($user->sheetsId(), $batchUpdateRequest);
        }catch(\Google_Service_Exception $exception){
            $code = $exception->getCode();
            switch($code){
                case'401':
                    header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
                    exit;
                default:
                    /*dd($exception->getErrors());*/
                    return view('errors.googlesheet')->with('errors', $exception->getErrors());

            }
        }

        return $response;
    }
}
