<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealEstate extends Model
{
    //
    protected $table = 'realestates';
    protected $fillable = [
        'name',
        'address',
        'lat',
        'lng',
        'user_id',
    ];

    public function priceTags(){
        return $this->hasMany('App\PriceTag');
    }
    public function earningRate(){
        return $this->hasOne('App\EarningRate', 'realestate_id');
    }
    public function loan(){
        return $this->hasOne('App\Loan', 'realestate_id');
    }
}
