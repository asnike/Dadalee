<?php

namespace App\Http\Controllers;

use App\RentalCost;
use Illuminate\Http\Request;

class RentalCostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\RentalCost  $rentalCost
     * @return \Illuminate\Http\Response
     */
    public function show($no)
    {
        //
        $bunji = explode(',', $no);
        $rentalcosts = RentalCost::where('main_no', $bunji[0])
            ->where('sub_no', $bunji[1])
            ->orderBy('yearmonth', 'desc')
            ->get();
        return response()->json([
            'result'=>1,
            'rentalcosts'=>$rentalcosts
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RentalCost  $rentalCost
     * @return \Illuminate\Http\Response
     */
    public function edit(RentalCost $rentalCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RentalCost  $rentalCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RentalCost $rentalCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RentalCost  $rentalCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(RentalCost $rentalCost)
    {
        //
    }
}
