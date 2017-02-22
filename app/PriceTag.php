<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceTag extends Model
{
    //
    protected $table = 'pricetags';
    protected $fillable = [
        'sigungu',
        'main_no',
        'sub_no',
        'building_name',
        'new_address',
        'lng',
        'lat',
        'user_id',
        'reported_at',
        'price',
        'deposit',
        'rental_cost',
        'floor',
        'completed_at',
    ];
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
