<?php

namespace App\Http\Controllers;

use App\Sigungu;
use Illuminate\Http\Request;

class SigungusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sigungus = Sigungu::all();
        return response()->json([
            'result' => 1,
            'lists' => $sigungus
        ]);
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
     * @param  \App\Sigungu  $sigungu
     * @return \Illuminate\Http\Response
     */
    public function show(Sigungu $sigungu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sigungu  $sigungu
     * @return \Illuminate\Http\Response
     */
    public function edit(Sigungu $sigungu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sigungu  $sigungu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sigungu $sigungu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sigungu  $sigungu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sigungu $sigungu)
    {
        //
    }
}
