<?php

namespace App\Http\Controllers;

use App\ActualPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ActualPricesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contain(Request $request, $latlng){
        $range = explode(',', $latlng);
        $query = ActualPrice::where('lat', '>=', $range[0])
            ->where('lat', '<=', $range[2])
            ->where('lng', '>=', $range[1])
            ->where('lng', '<=', $range[3])
            /*->groupBy(['main_no', 'sub_no'])*/
            ->distinct()
            ->orderBy('yearmonth', 'desc');
        $actualPrices = Redis::get($latlng);
        if(!$actualPrices){
            $actualPrices = $query->get();
            Redis::set($latlng, $actualPrices);
        }else{
            $actualPrices = \GuzzleHttp\json_decode($actualPrices);
        }

        return response()->json([
            'result' => 1,
            'debug'=>[
                'sql'=>$query->toSql()
            ],
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
    public function show($no)
    {
        //
        $bunji = explode(',', $no);
        $query = ActualPrice::where('main_no', (int)$bunji[0])
            ->where('sub_no', (int)$bunji[1])
            ->orderBy('yearmonth', 'desc');
        $actualprices = $query->get();
        return response()->json([
            'result'=>1,
            'debug'=>[
                'sql'=>$query->toSql(),
                'bunji'=>$bunji
            ],
            'actualprices'=>$actualprices
        ]);
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
