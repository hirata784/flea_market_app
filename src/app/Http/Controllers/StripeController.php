<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET')); //シークレットキー

        $charge = Charge::create(array(
            'amount' => $request->price,
            'currency' => 'jpy',
            'source' => request()->stripeToken,
        ));
        return back();
    }
}
