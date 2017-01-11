<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    protected $fillable = [
        ''
    ];

    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
