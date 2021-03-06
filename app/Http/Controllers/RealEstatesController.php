<?php

namespace App\Http\Controllers;

use App\EarningRate;
use App\Loan;
use App\RealEstate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Facades\Parser;

class RealEstatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //
        header('Vary:X-Requested-With');
        $realestates = RealEstate::with(['earningRate'])->where('user_id', auth()->id())->where('market', 0)->get();
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
        $this->validate($request, [
            'name'=>'required',
            'address'=>'required',
            ''
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['own'] = $request->input('own') == 'true' ? 1 : 0;

        $realestate = RealEstate::create($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$realestate->id,
                'name'=>$realestate->name
            ],
            'msg'=>trans('common.addSuccess')
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
        $this->validate($request, [
            'name'=>'required',
            'address'=>'required',
            'floor'=>'numeric',
            'completed_at'=>'nullable',
            'exclusive_size'=>'numeric',
            'memo'=>'nullable',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['own'] = $request->has('own');
        $realestate = RealEstate::findOrFail($id);
        $realestate->update($data);

        return response()->json([
            'result'=>1,
            'data'=>[
                'realestate'=>$realestate,
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
    public function destroy($id)
    {
        //
        $realestate = RealEstate::findOrFail($id);
        $realestate->earningRate()->delete();
        $realestate->loan()->delete();
        $realestate->delete();

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$id
            ]
        ]);
    }

    public function earning(Request $request, $id){
        $this->validate($request, [
            'price'=>'required',
            'deposit'=>'required',
            'monthlyfee'=>'nullable',
            'investment'=>'nullable',
            'mediation_cost'=>'nullable',
            'judicial_cost'=>'nullable',
            'tax'=>'nullable',
            'etc_cost'=>'nullable',
            'interest_amount'=>'required',
            'real_earning'=>'nullable',
        ]);

        $realestate = RealEstate::findOrFail($id);
        $earningRate = EarningRate::updateOrCreate(['realestate_id'=>$id], $request->except(['_method']));

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$realestate->id,
                'earningrate'=>$earningRate
            ],
            'msg'=>trans('common.realestate_add_success')
        ]);
    }

    public function loan(Request $request, $id){
        $this->validate($request, [
            'amount'=>'integer|required',
            'interest_rate'=>'numeric|required',
            'repay_commission'=>'numeric|required',
            'unredeem_period'=>'integer',
            'repay_period'=>'integer|required',
            'bank'=>'nullable',
            'account_no'=>'nullable',
            'options'=>'nullable',
        ]);

        $realestate = RealEstate::findOrFail($id);
        $earningRate = EarningRate::updateOrCreate(['realestate_id'=>$id], $request->only([
            'investment',
            'rate',
        ]));
        $loan = Loan::updateOrCreate(['realestate_id'=>$id], $request->except(['_method']));

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$realestate->id,
                'loan'=>$loan
            ],
            'msg'=>trans('common.realestate_add_success')
        ]);
    }

    public function tenant(Request $request, $id){
        $realestate = RealEstate::findOrFail($id);
        $tenant = $realestate->tenant();
        if($tenant){
            $tenant->update($request->except(['_method']));
        }else{
            $data = array_merge($request->except(['_method']), ['realestate_id'=>$realestate->id]);
            $tenant->create($data);
        }

        return response()->json([
            'result'=>1,
            'data'=>[
                'id'=>$realestate->id,
                'tenant'=>$realestate->tenant()
            ],
            'msg'=>trans('common.realestate_add_success')
        ]);
    }

    public function tradePrice(Request $request){
        $client = new Client();
        $res = $client->get('http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcRHTrade?serviceKey=zuAaDK5U8Aq8gEqZqUMJtdrh8EqsfFyJbLJhWXF8Qizu53BgkMSDjNSCixt30QNPx%2Bk5oea4im7bYVSBsR%2BS2A%3D%3D&LAWD_CD=28245&DEAL_YMD=201612');
        dd(Parser::xml($res->getBody()));
    }
}
