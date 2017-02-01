<?php

namespace App\Http\Controllers\Admin;

use App\ActualPrice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ActualPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.actualprice.list', ['prices'=>ActualPrice::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function numeric($a) {
        $a = str_replace(",", "", $a);
        return $a;
    }
    public function store(Request $request)
    {
        //
        $path = $request->file('excel')->storeAs('excels', 'data.xlsx', 'public');
        //dd(Storage::url('app/public/excels/data.xlsx'));
        //exit;
        Excel::load(Storage::url('app/public/excels/data.xlsx'), function($reader){
            $data = $reader->toObject();

            foreach ($data as $row){
                ActualPrice::create([
                    'sigungu'=>$row[1],
                    'main_no'=>$row[2],
                    'sub_no'=>$row[3],
                    'building_name'=>$row[4],
                    'exclusive_size'=>$row[5],
                    'land_size'=>$row[6],
                    'yearmonth'=>$row[7],
                    'day'=>$row[8],
                    'price'=>$this->numeric($row[9]),
                    'floor'=>$row[10],
                    'completed_at'=>$row[11],
                    'new_address'=>$row[12],
                ]);
            }


        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ActualPrice  $actualPrice
     * @return \Illuminate\Http\Response
     */
    public function show(ActualPrice $actualPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ActualPrice  $actualPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(ActualPrice $actualPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ActualPrice  $actualPrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActualPrice $actualPrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ActualPrice  $actualPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActualPrice $actualPrice)
    {
        //
    }
}
