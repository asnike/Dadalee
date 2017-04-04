<?php

namespace App\Http\Controllers;



use App\Libraries\Google\Facades\GoogleApiFacade;
use App\RealEstate;
use App\User;
use Illuminate\Http\Request;


class GoogleSheetsController extends Controller
{
    //
    protected $requests;

    public function __construct()
    {
        GoogleApiFacade::init(
            'json/credential.json',
            route('auth2.callback'),
            \Google_Service_Sheets::SPREADSHEETS
        );
    }

    public function callback(){

    }
    public function export(Request $request){
        $realestate = RealEstate::with(['earningRate', 'loan'])->findOrFail($request->input('id'));

        $sheetname = $realestate->building_name.' '.trans('common.earningRateSheet');

        $result = GoogleApiFacade::auth()->addSheet($this->getSheetsId(), $sheetname);
        $result = GoogleApiFacade::auth()->addRealEstate($this->getSheetsId(), $sheetname, $realestate);

        return redirect()->route('realestates.index');
    }
    public function authCallback(Request $request){
        GoogleApiFacade::authCallback();
    }
    public function revoke(){
        dd(GoogleApiFacade::auth()->revoke());
    }

    public function linked(){

    }
    public function link(Request $request){
        $user = $this->getUser();
        $sheetsId = $user->sheetsId();
        if($sheetsId){

        }else{
            $result = GoogleApiFacade::auth()->createSpreadSheets('Dadalee Sheets');
            $user->update([
                'sheets_id'=>$result->spreadsheetId
            ]);
        }
        return view('mypage.sheetslink');
    }

    protected function getData($client, $sheetId)
    {
        $service = new \Google_Service_Sheets($client);
        $range = 'A1';
        $response = $service->spreadsheets_values->get($sheetId, $range);
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
    protected function getUser(){
        try {
            $user = User::findOrFail(auth()->id());
        }catch(\Exception $exception){
            $errors = array(array('message'=>'test'));
            return view('errors.googlesheet')->with('errors', $errors);
        }
        return $user;
    }
}
