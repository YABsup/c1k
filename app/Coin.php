<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    //
    public function reserv()
    {
        return $this->belongsTo('App\Reserv', 'coin_id', 'id');
    }
}
