<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    protected $fillable = [
        'realestate_id',
        'amount',
        'interest_rate',
        'repay_commission',
        'unredeem_period',
        'repay_period',
        'repay_method_id',
        'bank',
        'account_no',
        'options',
    ];

    public function realestate(){
        return $this->belongsTo('App\RealEstate');
    }
}
