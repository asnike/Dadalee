<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceTag extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
