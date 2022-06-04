<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserv extends Model
{
    protected $fillable = array('coin_id');
    //
    public function coin()
    {
        return $this->belongsTo('App\Coin');
    }
}
