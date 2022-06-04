<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    //
    public function user()
    {
      return $this->belongsTo('App\User','user_id');
    }

    public function admin()
    {
      return $this->belongsTo('App\User','user_approved');
    }
    
    public function status()
    {
      return $this->belongsTo('App\OrderStatus', 'status_id', 'id');
    }
}
