<?php

namespace App\Http\Controllers;

use App\ActualPrice;
use Illuminate\Http\Request;

class ActualPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contain(Request $request, $latlng){
        $range = explode(',', $latlng);
        /*$result = ActualPrice::whereBetween('lat', [$range[0], $range[2]])
            ->whereBetween('lng', [$range[1], $range[3]])
            ->toSql();
        dd($result);
        exit;*/

        $actualPrices = ActualPrice::whereBetween('lat', [$range[0], $range[2]])
            ->whereBetween('lng', [$range[1], $range[3]])
            ->get();

        return response()->json([
            'result' => 1,
            'lists' => $actualPrices
        ]);
    }

    public function index(Request $request)
    {
        //
        header('Vary:X-Requested-With');
        $actualPrices = ActualPrice::all();

        if($request->ajax()) {
            return response()->json([
                'result' => 1,
                'lists' => $actualPrices
            ]);
        }

        return view('actualprice.index');
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
    public function store(Request $request)
    {
        //
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
