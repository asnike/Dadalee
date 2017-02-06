<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActualPrice extends Model
{
    //
    protected $fillable = [
        'sigungu',
        'main_no',
        'sub_no',
        'building_name',
        'exclusive_size',
        'land_size',
        'yearmonth',
        'day',
        'price',
        'floor',
        'completed_at',
        'lng',
        'lat',
        'new_address',
    ];
    protected $table = 'actualprices';
}
