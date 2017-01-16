<?php

namespace App\Http\Controllers;

use App\RealEstate;
use Illuminate\Http\Request;

class RealEstatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        header('Vary:X-Requested-With');
        $realestates = RealEstate::where('user_id', auth()->id())->where('market', 0)->get();
        if($request->ajax()){
            return response()->json([
                'result'=>1,
                'lists'=>$realestates
            ]);
        }

        return view('realestate.index')->with('realestates', $realestates);
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
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['own'] = $request->has('own');
        $realestate = RealEstate::create($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$realestate->id,
                'name'=>$realestate->name
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RealEstate  $realEstate
     * @return \Illuminate\Http\Response
     */
    public function show(RealEstate $realEstate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RealEstate  $realEstate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $realEstate = RealEstate::with(['earningRate', 'loan'])->findOrFail($id);

        return response()->json([
            'result'=>1,
            'data'=>[
                'realestate'=>$realEstate,
                'earningrate'=>$realEstate->earningRate(),
                'loan'=>$realEstate->loan(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RealEstate  $realEstate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['own'] = $request->has('own');
        $realestate = RealEstate::findOrFail($id);
        $realestate->update($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'realestate'=>$realEstate,
                'earningrate'=>$realEstate->earningRate(),
                'loan'=>$realEstate->loan(),
            ],
            'msg'=>trans('common.realestate_add_success')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RealEstate  $realEstate
     * @return \Illuminate\Http\Response
     */
    public function destroy(RealEstate $realEstate)
    {
        //
    }
}
