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
}
