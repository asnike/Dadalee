<?php

namespace App\Http\Controllers;

use App\EarningRate;
use App\RealEstate;
use Illuminate\Http\Request;

class EarningRatesController extends Controller
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
        $this->validate($request, [
            'price'=>'required|numeric',
            'deposit'=>'required|numeric',
            'monthlyfee'=>'numeric',
            'investment'=>'numeric',
            'interest_amount'=>'numeric',
            'real_earning'=>'numeric',
        ]);

        $realestate_id = $request->input('realestate_id');

        $earningRate = RealEstate::find($realestate_id)->earningRate()->create([
            'price'=> $request->input('price'),
            'deposit'=> $request->input('deposit'),
            'monthlyfee'=> $request->input('monthlyfee'),
            'investment'=> $request->input('investment'),
            'interest_amount'=> $request->input('interest_amount'),
            'real_earning'=> $request->input('real_earning'),
        ]);

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$earningRate->id,
                'realestate_id'=>$realestate_id,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EarningRate  $earningRate
     * @return \Illuminate\Http\Response
     */
    public function show(EarningRate $earningRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EarningRate  $earningRate
     * @return \Illuminate\Http\Response
     */
    public function edit(EarningRate $earningRate, $id)
    {
        //
        $earningRate = EarningRate::find($id);

        return response()->json($earningRate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EarningRate  $earningRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $earningRate = EarningRate::findOrFail($id);

        $earningRate->update($request->all());

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$earningRate->id,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EarningRate  $earningRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EarningRate $earningRate)
    {
        //
    }
}
