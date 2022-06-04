<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    //
    protected $guarded = ['uuid','g-recaptcha-response'];

    protected $casts = [
        'sepa' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function status()
    {
      return $this->belongsTo('App\OrderStatus', 'status_id', 'id');
    }

    public function pair()
    {
        return $this->belongsTo('App\Pair', 'category_pair_id', 'id');
    }
}
