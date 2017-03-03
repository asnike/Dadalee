<?php

namespace App\Http\Controllers;

use App\PriceTag;
use App\RealEstate;
use Illuminate\Http\Request;

class PriceTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        $this->middleware('auth');
    }

    public function contain(Request $request, $latlng){
        $range = explode(',', $latlng);
        $query = PriceTag::where('user_id', auth()->id())
            ->where('lat', '>=', $range[0])
            ->where('lat', '<=', $range[2])
            ->where('lng', '>=', $range[1])
            ->where('lng', '<=', $range[3])
            ->distinct()
            ->groupBy(['main_no', 'sub_no'])
            ->orderBy('reported_at', 'desc');

        $priceTags = $query->get();

        return response()->json([
            'result' => 1,
            'debug'=>[
                'sql'=>$query->toSql()
            ],
            'lists' => $priceTags
        ]);
    }

    public function index(Request $request)
    {
        //
        header('Vary:X-Requested-With');
        $realestates = PriceTag::where('user_id', auth()->id())->groupBy(['main_no', 'sub_no'])->get();
        if($request->ajax()){
            return response()->json([
                'result'=>1,
                'lists'=>$realestates
            ]);
        }

        return view('pricetag.index')->with('realestates', $realestates);
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
        $this->validate($request, [
            'sigungu'=>'string|required',
            'main_no'=>'string|required',
            'sub_no'=>'string|required',
            'building_name'=>'string|required',
            'new_address'=>'string',
            'lat'=>'string|required',
            'lng'=>'string|required',
        ]);
        $data = $request->only([
            'sigungu',
            'main_no',
            'sub_no',
            'building_name',
            'new_address',
            'lat',
            'lng',
            'reported_at',
            'completed_at',
            'exclusive_size',
            'price',
            'deposit',
            'rental_cost',
            'floor',
        ]);
        $data['user_id'] = auth()->id();
        $pricetag = PriceTag::create($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'pricetag'=>$pricetag
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function show($no)
    {
        $bunji = explode(',', $no);
        $query = PriceTag::where('main_no', (int)$bunji[0])
            ->where('sub_no', (int)$bunji[1])
            ->orderBy('reported_at', 'desc');
        $pricetags = $query->get();
        return response()->json([
            'result'=>1,
            'debug'=>[
                'sql'=>$query->toSql(),
                'bunji'=>$bunji
            ],
            'pricetags'=>$pricetags
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceTag $priceTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceTag $priceTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $priceTag = PriceTag::findOrFail($id);
        $priceTag->delete();

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$id
            ]
        ]);
    }
}
