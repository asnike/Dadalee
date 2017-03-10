<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Sheets;
use Google;


class GoogleSheetsController extends Controller
{
    //
    public function export(Request $request){

    }
    public function import(){
        Sheets::setService(Google::make('sheets'));
        Sheets::spreadsheet('1U9Mavbb4HYIOPEUQFF3yF8Xqdp0szcsRHATAL5nkzpU');

        $values = Sheets::sheet('test')->all();

        dd($values);
    }
}
