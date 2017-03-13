<?php

namespace App\Http\Controllers;


use GoogleSheet\tests\SheetsTest;
use Illuminate\Http\Request;
use Sheets;
use Google;


class GoogleSheetsController extends Controller
{
    //
    public function callback(){

    }
    public function export(Request $request){

    }
    public function import(){
        $googleClient = Google::getClient();


        Sheets::setService(Google::make('sheets'));
        Sheets::spreadsheet('1tZPhTDt22Qeh6bT--TVbhj3cKq-jDew1e5GUm4KlCj8');

        Sheets::sheet('test')->range('')->append([['4','asnike@gmail.com','02020202']]);

        $values = Sheets::sheet('test')->all();
        dd($values);
    }
}
