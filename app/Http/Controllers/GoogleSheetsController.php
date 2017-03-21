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

        $filename = $realestate->building_name.' '.trans('common.earningRateSheet');

        $client = $this->authCheck($request->session()->get('access_token'));
        $result = $this->addSheet($client, $filename, TRUE)->batchUpdate($client);
        $result = $this->addRealEstate($client, $filename, $realestate);

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
        $sheetsId = $this->getSheetsId();
        if($sheetsId){

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
    protected function addRealEstate($client, $sheetName, $realestate){
        $sheetName = "'".$sheetName."'";
        $service = new \Google_Service_Sheets($client);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateValuesRequest(
            [
                'data'=>[
                    'range'=>$sheetName.'!A1',
                    'values'=>[
                        array(trans('common.basicInfo')),
                        [trans('common.address'), $realestate->address],
                        [trans('common.floor'), $realestate->floor],
                        [trans('common.completed_at'), $realestate->completed_at],
                        [trans('common.exclusiveSize'), $realestate->exclusive_size.'('.$realestate->exclusive_size/3.3.trans('common.p').')'],
                        [trans('common.memo'), $realestate->memo],
                        [trans('common.spend')],
                        [trans('common.tradePrice'), $realestate->earningRate?$realestate->earningRate->price:''],
                        [trans('common.tax'), $realestate->earningRate?$realestate->earningRate->tax:''],
                        [trans('common.mediation_cost'), $realestate->earningRate?$realestate->earningRate->mediation_cost:''],
                        [trans('common.judicial_cost'), $realestate->earningRate?$realestate->earningRate->judicial_cost:''],
                        [trans('common.etc_cost'), $realestate->earningRate?$realestate->earningRate->etc_cost:''],
                        [trans('common.sumTotal'), '=SUM('.$sheetName.'!B8:B12)'],
                        [trans('common.loan')],
                        [trans('common.loanAmount'), $realestate->loan?$realestate->loan->amount:''],
                        [trans('common.loanInterestRate'), $realestate->loan?(float)$realestate->loan->interest_rate/100:''],
                        [trans('common.repayCommission'), $realestate->loan?(float)$realestate->loan->repay_commission/100:''],
                        [trans('common.loanInterestAmount'), '=('.$sheetName.'!B15*'.$sheetName.'!B16)/12'],
                        [trans('common.import')],
                        [trans('common.deposit'), $realestate->earningRate?$realestate->earningRate->deposit:''],
                        [trans('common.monthlyFee'), $realestate->earningRate?$realestate->earningRate->monthlyfee:''],
                        [trans('common.conclusion')],
                        [trans('common.actualInvestment'), '=('.$sheetName.'!B13-'.$sheetName.'!B15-'.$sheetName.'!B20)'],
                        [trans('common.actualEarning'), '=('.$sheetName.'!B21-'.$sheetName.'!B18)'],
                        [trans('common.earningRateYear'), '=('.$sheetName.'!B24*12/'.$sheetName.'!B23)'],
                    ],
                ],
                'valueInputOption'=>'USER_ENTERED'
            ]
        );
        $response = $service->spreadsheets_values->batchUpdate($this->getSheetsId(), $batchUpdateRequest);

        return $response;
    }
    protected function batchUpdate($client){

        $service = new \Google_Service_Sheets($client);
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
                    /*dd($exception->getErrors());*/
                    return view('errors.googlesheet')->with('errors', $exception->getErrors());

            }
        }

        return $response;
    }
    protected function getSheetsId(){
        try {
            $user = User::findOrFail(auth()->id());
        }catch(\Exception $exception){
            $errors = array(array('message'=>'test'));
            return view('errors.googlesheet')->with('errors', $errors);
        }
        return $user->sheetsId();
    }
}
