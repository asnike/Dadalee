<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentalCost extends Model
{
    //
    protected $table = 'rental_costs';

    protected $fillable = [
        'sigungu',
        'main_no',
        'sub_no',
        'building_name',
        'exclusive_size',
        'land_size',
        'yearmonth',
        'day',
        'deposit',
        'rental_cost',
        'rental_type',
        'floor',
        'completed_at',
        'lng',
        'lat',
        'new_address',
    ];
}
