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
        $realestates = RealEstate::where('user_id', auth()->id())->where('market', 1)->get();
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
        $data = $request->only(['addr', 'lat', 'lng']);
        $data['user_id'] = auth()->id();
        $realestate = RealEstate::firstOrCreate($data);

        $data = $request->only(['price', 'deposit', 'monthlyfee']);
        $data['realestate_id'] = $realestate->id;
        $pricetag = $realestate->priceTags()->create($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'realestate_id'=>$realestate->id,
                'pricetag_id'=>$pricetag->id,
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
