<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;

class LoansController extends Controller
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
        $this->validate($request, [
            'amount'=>'required|numeric',
            'interest_rate'=>'required|numeric',
            'repay_commission'=>'required|numeric',
            'unredeem_period'=>'numeric',
            'repay_period'=>'numeric',
            'repay_method_id'=>'numeric|in:App\RepayMethod',
            'bank'=>'alpha',
            'account_no'=>'numeric',
        ]);

        $realestate_id = $request->input('realestate_id');

        $loan = RealEstate::find($realestate_id)->loan()->create([
            'amount'=> $request->input('amount'),
            'interest_rate'=> $request->input('interest_rate'),
            'repay_commission'=> $request->input('repay_commission'),
            'unredeem_period'=> $request->input('unredeem_period'),
            'repay_period'=> $request->input('repay_period'),
            'repay_method_id'=> $request->input('repay_method_id'),
            'bank'=>$request->input('bank'),
            'account_no'=>$request->input('account_no'),
        ]);

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$loan->id,
                'realestate_id'=>$realestate_id,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
