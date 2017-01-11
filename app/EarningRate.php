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
    ];
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
