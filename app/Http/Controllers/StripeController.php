<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe_order(Request $request)
    {

        $stripe = new \Stripe\StripeClient(
            'sk_test_51IH8bgCzO7zVUL4KFmZXn8452T1q8jZ10XJwFhbAmwMSzUp8WFCXb7pbuRutMBWrWxKydL7UqfLd8T7AODwmkHTH00Duq16wDr'
        );
        $result = $stripe->tokens->create([
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc,
            ],
        ]);

        if (!$request->cost || $request->cost < 20) {
            return response()->json([
                "status" => false,
                'message' => 'Cost should not be less than 20EGP',
            ], 401);
        }

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51IH8bgCzO7zVUL4KFmZXn8452T1q8jZ10XJwFhbAmwMSzUp8WFCXb7pbuRutMBWrWxKydL7UqfLd8T7AODwmkHTH00Duq16wDr');

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $result->id;

        $charge = \Stripe\Charge::create([
            'amount' => $request->cost * 100,
            'currency' => 'egp',
            'description' => 'Add points to Inline app wallet',
            'source' => $token,
            'metadata' => ['order_id' => uniqid('', true)],
        ]);

        if ($charge->status !== 'succeeded') {
            return response()->json([
                "status" => false,
                'message' => 'Try again',
            ], 401);
        }
        $user = User::find(auth()->user()->id);
        $user->update([
            'wallet' => auth()->user()->wallet + $request->cost
        ]);
        return response()->json([
            "status" => true,
            "result" => [
                'message' => 'successfully wallet charged',
                'wallet' => auth()->user()->wallet + $request->cost
            ]
        ], 201);
    }
}
