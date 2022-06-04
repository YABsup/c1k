<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anketa extends Model
{
    //
    protected $guarded = array('id','user_id','g-recaptcha-response');

    public function user()
    {
      return $this->belongsTo('\App\User', 'user_id');
    }

}
