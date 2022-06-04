<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ApiSign implements Rule
{
    private $request;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $api_secret = $this->request->user()->api_secret;

        if($api_secret == null)
        {
            return false;
        }

        $api_token = $this->request->get('api_token');
        $currency =  $this->request->get('currency');
        $address = $this->request->get('address');


        $sign = hash_hmac( 'SHA256', $currency.$address.$api_token,  $api_secret);

        return $sign === $value;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute error.';
    }
}
