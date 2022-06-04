<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    //
    protected $guarded = ['symbol'];

    public function base_currency()
    {
        return $this->belongsTo('App\Coin', 'base_currency_id');
    }
    public function quote_currency()
    {
        return $this->belongsTo('App\Coin', 'quote_currency_id');
    }

    public function base_reserv()
    {
        return $this->belongsTo('App\Reserv', 'base_currency_id', 'coin_id');
    }
    public function quote_reserv()
    {
        return $this->belongsTo('App\Reserv', 'quote_currency_id', 'coin_id');
    }

    public function provider()
    {
        return $this->belongsTo('App\PriceProvider', 'provider_id');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function reserv()
    {
        return $this->belongsTo('App\Reserv');
    }

    static public function update_paired( $pair )
    {
        if( in_array( $pair->base_currency_id, [14,184,186] ) && in_array( $pair->quote_currency_id, [178,179,180,181,182,183,191] ) )
        {
            foreach( [14,184,186] as $base_currency_id )
            {
                \App\Pair::where('city_id','=',$pair->city_id)
                ->where('base_currency_id','=',$base_currency_id)
                ->where('quote_currency_id','=',$pair->quote_currency_id)
                ->update([
                    'provider_id'=>$pair->provider_id,

                    'bid_coef'=>$pair->bid_coef,
                    'ask_coef'=>$pair->ask_coef,

                    'base_min'=>$pair->base_min,
                    'base_max'=>$pair->base_max,
                    'quote_min'=>$pair->quote_min,
                    'quote_max'=>$pair->quote_max,

                    'bid_position'=> $pair->bid_position,
                    'ask_position'=> $pair->ask_position,
                    'bid_step'=> $pair->bid_step,
                    'ask_step'=> $pair->ask_step,
                ]);
            }
        }elseif( in_array( $pair->quote_currency_id, [14,184,186] ) && in_array( $pair->base_currency_id, [178,179,180,181,182,183,191] ) ){
            foreach( [14,184,186] as $quote_currency_id )
            {
                \App\Pair::where('city_id','=',$pair->city_id)
                ->where('base_currency_id','=',$pair->base_currency_id)
                ->where('quote_currency_id','=',$quote_currency_id)
                ->update([
                    'provider_id'=>$pair->provider_id,

                    'bid_coef'=>$pair->bid_coef,
                    'ask_coef'=>$pair->ask_coef,

                    'base_min'=>$pair->base_min,
                    'base_max'=>$pair->base_max,
                    'quote_min'=>$pair->quote_min,
                    'quote_max'=>$pair->quote_max,

                    'bid_position'=> $pair->bid_position,
                    'ask_position'=> $pair->ask_position,
                    'bid_step'=> $pair->bid_step,
                    'ask_step'=> $pair->ask_step,
                ]);
            }
        }



        $ref_cities = \App\City::where('ref_city_id','=',$pair->city_id )->get();
        foreach( $ref_cities as $ref_city )
        {
            $ref_pair_bid_coef = $pair->bid_coef - ($ref_city->ref_bid_coef/100 * $pair->bid_coef );
            $ref_pair_ask_coef = $pair->ask_coef + ($ref_city->ref_ask_coef/100 * $pair->ask_coef);

            if( in_array( $pair->base_currency_id, [14,184,186] ) && in_array( $pair->quote_currency_id, [178,179,180,181,182,183,191] ) )
            {
                foreach( [14,184,186] as $base_currency_id )
                {
                    \App\Pair::where('city_id','=',$ref_city->id)
                    ->where('base_currency_id','=',$base_currency_id)
                    ->where('quote_currency_id','=',$pair->quote_currency_id)
                    ->update([
                        'provider_id'=>$pair->provider_id,

                        'bid_coef'=>$ref_pair_bid_coef,
                        'ask_coef'=>$ref_pair_ask_coef,

                        'base_min'=>$pair->base_min,
                        'base_max'=>$pair->base_max,
                        'quote_min'=>$pair->quote_min,
                        'quote_max'=>$pair->quote_max,

                        'bid_position'=> $pair->bid_position,
                        'ask_position'=> $pair->ask_position,
                        'bid_step'=> $pair->bid_step,
                        'ask_step'=> $pair->ask_step,
                    ]);
                }
            }elseif( in_array( $pair->quote_currency_id, [14,184,186] ) && in_array( $pair->base_currency_id, [178,179,180,181,182,183,191] ) ){
                foreach( [14,184,186] as $quote_currency_id )
                {
                    \App\Pair::where('city_id','=',$ref_city->id)
                    ->where('base_currency_id','=',$pair->base_currency_id)
                    ->where('quote_currency_id','=',$quote_currency_id)
                    ->update([
                        'provider_id'=>$pair->provider_id,

                        'bid_coef'=>$ref_pair_bid_coef,
                        'ask_coef'=>$ref_pair_ask_coef,


                        'base_min'=>$pair->base_min,
                        'base_max'=>$pair->base_max,
                        'quote_min'=>$pair->quote_min,
                        'quote_max'=>$pair->quote_max,


                        'bid_position'=> $pair->bid_position,
                        'ask_position'=> $pair->ask_position,
                        'bid_step'=> $pair->bid_step,
                        'ask_step'=> $pair->ask_step,
                    ]);
                }
            }else{
                \App\Pair::where('city_id','=',$ref_city->id)
                ->where('base_currency_id','=',$pair->base_currency_id)
                ->where('quote_currency_id','=',$pair->quote_currency_id)
                ->update([
                    'provider_id'=>$pair->provider_id,

                    'bid_coef'=>$ref_pair_bid_coef,
                    'ask_coef'=>$ref_pair_ask_coef,

                    'base_min'=>$pair->base_min,
                    'base_max'=>$pair->base_max,
                    'quote_min'=>$pair->quote_min,
                    'quote_max'=>$pair->quote_max,

                    'bid_position'=> $pair->bid_position,
                    'ask_position'=> $pair->ask_position,
                    'bid_step'=> $pair->bid_step,
                    'ask_step'=> $pair->ask_step,
                ]);
            }
        }

    }

}
