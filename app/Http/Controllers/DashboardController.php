<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function show()
    {
        /** @var \App\Models\Account */
        $account = auth()->user();

        $subscription = $account->subscription('default');
        $plan = $subscription->asStripeSubscription(expand: ['plan.product'])->plan;
        $upcomingInvoice = $subscription->upcomingInvoice();
        $invoices = $account->invoices(parameters: ['expand' => ['data.subscription.plan.product']]);

        // dd($subscription);
        // dd($paymentMethod = $account->paymentMethods());

        return view('dashboard', compact(
            'subscription',
            'plan',
            'upcomingInvoice',
            'invoices',
        ));
    }

    public function redirectToBillingPortal()
    {
        return request()->user()->redirectToBillingPortal(route('dashboard'));
    }
}
