<?php

namespace App\Http\Controllers;

use App\RealEstate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelsController extends Controller
{
    //
    public function exportRealEstate(Request $request, $id){
        $realestate = RealEstate::with(['earningRate', 'loan'])->findOrFail($id);
        $filename = $realestate->building_name.' '.trans('common.earningRateSheet');
        $date = Carbon::now();
        Excel::create($filename.'_'.$date, function($excel) use($realestate, $filename){
            $excel->sheet($filename, function($sheet) use($realestate){
                $sheet->setWidth(array(
                    'A'=>30,
                    'B'=>50,
                ));
                $sheet->setBorder('A1:B25', 'thin', '#dddddd');
                $sheet->cells('A1:A25', function($cells){
                    $cells->setBackground('#306519');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                });

                $sheet->setColumnFormat(array(
                    'B7:B24'=>'0,0'
                ));

                $sheet->setColumnFormat(array(
                    'B16:B17'=>'0.00%'
                ));
                $sheet->setColumnFormat(array(
                    'B18:B18'=>'0'
                ));
                $sheet->setColumnFormat(array(
                    'B24:B24'=>'0,0'
                ));
                $sheet->setColumnFormat(array(
                    'B25:B25'=>'0.00%'
                ));
                $sheet->fromArray(array(
                    array(trans('common.basicInfo')),
                    array(trans('common.address'), $realestate->address),
                    array(trans('common.floor'), $realestate->floor),
                    array(trans('common.completed_at'), $realestate->completed_at),
                    array(trans('common.exclusiveSize'), $realestate->exclusive_size.'('.$realestate->exclusive_size/3.3.trans('common.p').')'),
                    array(trans('common.memo'), $realestate->memo),
                    array(trans('common.spend')),
                    array(trans('common.tradePrice'), $realestate->earningRate->price),
                    array(trans('common.tax'), $realestate->earningRate->tax),
                    array(trans('common.mediation_cost'), $realestate->earningRate->mediation_cost),
                    array(trans('common.judicial_cost'), $realestate->earningRate->judicial_cost),
                    array(trans('common.etc_cost'), $realestate->earningRate->etc_cost),
                    array(trans('common.sumTotal'), '=SUM(B8:B12)'),
                    array(trans('common.loan')),
                    array(trans('common.loanAmount'), $realestate->loan->amount),
                    array(trans('common.loanInterestRate'), (float)$realestate->loan->interest_rate/100),
                    array(trans('common.repayCommission'), (float)$realestate->loan->repay_commission/100),
                    array(trans('common.loanInterestAmount'), '=(B15*B16)/12'),
                    array(trans('common.import')),
                    array(trans('common.deposit'), $realestate->earningRate->deposit),
                    array(trans('common.monthlyFee'), $realestate->earningRate->monthlyfee),
                    array(trans('common.conclusion')),
                    array(trans('common.actualInvestment'), '=(B13-B15-B20)'),
                    array(trans('common.actualEarning'), '=(B21-B18)'),
                    array(trans('common.earningRateYear'), '=(B24*12/B23)'),
                ), null, 'A1', false, false);

                $sheet->cells('A1:B1', function($cells){
                    $cells->setBackground('#3d85c6');
                });
                $sheet->cells('A7:B7', function($cells){
                    $cells->setBackground('#3d85c6');
                });
                $sheet->cells('A14:B14', function($cells){
                    $cells->setBackground('#3d85c6');
                });
                $sheet->cells('A19:B19', function($cells){
                    $cells->setBackground('#3d85c6');
                });
                $sheet->cells('A22:B22', function($cells){
                    $cells->setBackground('#3d85c6');
                });

                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('A14:B14');
                $sheet->mergeCells('A19:B19');
                $sheet->mergeCells('A22:B22');
                /*$sheet->setAutoSize(true);*/
           });
        })->download('xls');
    }
}
