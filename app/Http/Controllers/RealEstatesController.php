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
            'name'=>'required|max:50',
            'address'=>'required|max:255',
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
            'name'=>'required|max:50',
            'address'=>'required|max:255',
            'floor'=>'numeric|max:10',
            'completed_at'=>'max:15',
            'exclusive_size'=>'numeric',
            'memo'=>'max:500',
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
            'price'=>'required|max:20',
            'deposit'=>'required|max:20',
            'monthlyfee'=>'max:10',
            'investment'=>'max:20',
            'mediation_cost'=>'max:10',
            'judicial_cost'=>'max:20',
            'tax'=>'max:20',
            'etc_cost'=>'max:20',
            'interest_amount'=>'required|max:20',
            'real_earning'=>'max:20',
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
            'amount'=>'required|max:20',
            'interest_rate'=>'required|max:5',
            'repay_commission'=>'required|max:5',
            'unredeem_period'=>'max:10',
            'repay_period'=>'required|max:10',
            'bank'=>'max:10',
            'account_no'=>'max:20',
            'options'=>'max:200',
        ]);

        $realestate = RealEstate::findOrFail($id);
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
        $res = $client->get('http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcRHTrade?serviceKey=zuAaDK5U8Aq8gEqZqUMJtdrh8EqsfFyJbLJhWXF8Qizu53BgkMSDjNSCixt30QNPx%2Bk5oea4im7bYVSBsR%2BS2A%3D%3D&LAWD_CD=28245&DEAL_YMD=2016');
        dd(Parser::xml($res->getBody()));
    }
}
