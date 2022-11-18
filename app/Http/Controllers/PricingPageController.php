<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PricingPageController extends Controller
{
    public function show()
    {
        // Cache::forget('plans-from-stripe'); // Uncomment this to generate an updated plans array

        $plans = Cache::rememberForever('plans-from-stripe', function () {
            $productIds = [
                'prod_MmkDJiG1yu9wDN', // Basic plan
                'prod_Mnx08CzVvRtmAO', // Standard plan
                'prod_MmkHXzANEWTqeS', // Premium plan
            ];
            $products = \Stripe\Product::all([
                'ids' => $productIds,
                'active' => true
            ])->data;

            foreach ($products as $product) {
                $prices = \Stripe\Price::all([
                    'product' => $product->id,
                    'type' => 'recurring',
                ])->data;

                // Ordenar os preços corretamente (o mensal deve estar no primeiro lugar)
                usort($prices, function ($a, $b) {
                    return match ($a->recurring->interval) {
                        'year' => 1,
                        'month' => -1,
                    };
                });
                $product['prices'] = [...$prices];
            }

            // Ordenar produtos corretamente
            usort($products, function ($a, $b) {
                return match ($a->name) {
                    'Basic' => -1,
                    'Standard' => 0,
                    'Premium' => 1,
                };
            });
            return $products;
        });
        // dd($plans);

        return view('pricing', [
            'plans' => $plans,
            'highlightPlan' => 'Standard',
            'showMonthlyPlansByDefault' => true,
            'faq' => [
                'How do I view and manage my subscription?' => "You can view your account's subscription, your other paid features and products, and your next billing date in your account's billing settings.",
                "How can I change my GitHub subscription?" => "You can upgrade or downgrade your subscription or cancel on https://localhost/dashboard.",
                "What happens if payment fails? " => "After an initial failed payment, we apply a 7 day grace period on your account and attempt to process a payment each week. After three failed payments, paid features are locked.",
            ],
        ]);
    }

    public function redirectToCheckout(Request $request)
    {
        $request->validate([
            'price-id' => ['required', 'string', 'starts_with:price_'],
            'recurring-interval' => ['required', 'string', 'in:year,month'],
            'plan' => ['required', 'string'],
        ]);
        $priceId = $request->input('price-id');

        /** @var \App\Models\Account */
        $account = Auth::user();

        if ($account->subscribed('default')) {
            return redirect()->route('dashboard')
                ->with('info', 'Você já tem uma assinatura em andamento. Clique em "Gerenciar assinatura" para mudar de plano.');
        }

        // Redirect to Stripe Checkout
        return $account->newSubscription('default', $priceId)
            // ->trialDays(31)
            // ->withPromotionCode('promo_1M5M4lHcBcdIHl3NFcUl9u0e')
            ->allowPromotionCodes()
            ->checkout([
                'payment_method_types' => ['card'],
                'success_url' => route('landing', ['subscribed' => true]), /* route('dashboard') */
                'cancel_url' => route('prices'),
                'customer_update' => [
                    'name' => 'auto'
                ]
            ]);
    }
}
