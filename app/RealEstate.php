<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealEstate extends Model
{
    //
    protected $table = 'realestates';
    protected $casts = [
      'own'=>'boolean',
    ];
    protected $fillable = [
        'name',
        'address',
        'sigungu',
        'sigungu_code',
        'main_no',
        'sub_no',
        'new_address',
        'building_name',
        'lat',
        'lng',
        'user_id',
        'floor',
        'completed_at',
        'exclusive_size',
        'memo',
        'own'
    ];


    public function earningRate(){
        return $this->hasOne('App\EarningRate', 'realestate_id');
    }
    public function loan(){
        return $this->hasOne('App\Loan', 'realestate_id');
    }
    public function tenant(){
        return $this->hasOne('App\Tenant', 'realestate_id');
    }
}
