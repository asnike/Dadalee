<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EarningRate extends Model
{
    //
    protected $table ='earningrates';
    protected $fillable = [
        'realestate_id',
        'price',
        'deposit',
        'monthlyfee',
        'investment',
        'interest_amount',
        'real_earning'
    ];
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}