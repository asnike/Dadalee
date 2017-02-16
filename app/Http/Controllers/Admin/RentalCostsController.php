<?php

namespace App\Http\Controllers\Admin;

use App\RentalCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $query = RentalCost::query();
        $costs = $query->paginate(15);
        return view('admin.rentalcost.list', ['costs'=>$costs]);
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
        $path = $request->file('excel')->storeAs('excels', 'data.xlsx', 'public');
        //dd(Storage::url('app/public/excels/data.xlsx'));
        //exit;
        Excel::load(Storage::url('app/public/excels/data.xlsx'), function($reader){
            $data = $reader->toObject();

            foreach ($data as $row){
                RentalCost::create([
                    'sigungu'=>$row[1],
                    'main_no'=>$row[2],
                    'sub_no'=>$row[3],
                    'building_name'=>$row[4],
                    'rental_type'=>$row[5] == '월세' ? 1:2,
                    'exclusive_size'=>$row[6],
                    'land_size'=>$row[7],
                    'yearmonth'=>$row[8],
                    'day'=>$row[8],
                    'deposit'=>$this->numeric($row[9]),
                    'rental_cost'=>$this->numeric($row[10]),
                    'floor'=>$row[11],
                    'completed_at'=>$row[12],
                    'new_address'=>$row[13],
                ]);
            }
            redirect(route('admin.rental.index'));

        });
    }
    private function numeric($a) {
        $a = str_replace(",", "", $a);
        return $a;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RentalCost  $rentalCost
     * @return \Illuminate\Http\Response
     */
    public function show(RentalCost $rentalCost)
    {
        //
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
