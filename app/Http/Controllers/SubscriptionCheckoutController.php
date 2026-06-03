<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionCheckoutController extends Controller
{
    public function store(Request $request, string $plan)
    {
        $prices = [
            'monthly' => config('services.stripe.price_ai_monthly'),
            'yearly' => config('services.stripe.price_ai_yearly')
        ];

        abort_unless(isset($prices[$plan]), 404, 'Plan no válido');

        $checkout = $request->user()
            ->newSubscription('default', $prices[$plan])
            ->checkout([
                'success_url' => route('billing.success'),
                'cancel_url' => route('billing.cancel'),

            ]);
        
        return Inertia::location($checkout->url);
    }
}
