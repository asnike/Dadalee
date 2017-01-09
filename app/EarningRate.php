<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EarningRate extends Model
{
    //
    protected $fillable = [
        'realestate_id',
        'price',
        'deposit',
        'investment',
    ];
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
