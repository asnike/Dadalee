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
    public function index(Request $request)
    {
        //
        $realestates = PriceTag::where('user_id', auth()->id())->get();
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
        $data = $request->only([
            'sigungu',
            'main_no',
            'sub_no',
            'building_name',
            'new_address',
            'lat',
            'lng',
            'reported_at',
            'compolete_at',
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
    public function show(PriceTag $priceTag)
    {
        //
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
    public function destroy(PriceTag $priceTag)
    {
        //
    }
}
