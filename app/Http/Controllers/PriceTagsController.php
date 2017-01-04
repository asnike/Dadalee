<?php

namespace App\Http\Controllers;

use App\PriceTag;
use Illuminate\Http\Request;

class PriceTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pricetag.index');
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
