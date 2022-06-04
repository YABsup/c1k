<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    public function country()
    {
      return $this->belongsTo('App\Country');
    }

    public function ref_city()
    {
      return $this->belongsTo('App\City','ref_city_id','id');
    }

}
