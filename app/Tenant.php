<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
