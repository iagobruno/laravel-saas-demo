<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived as WebhookData;
use Laravel\Cashier\Cashier;

class StripeWebhookReceived
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WebhookData $event)
    {
        $type = $event->payload['type'];
        $payload = $event->payload['data']['object'];
        // $account = Cashier::findBillable($payload['customer']);

        if ($type === 'customer.subscription.created') {
            $this->subscriptionCreated($payload);
        } //
        else if ($type === 'customer.subscription.updated') {
            $this->subscriptionUpdated($payload);
        } //
        else if ($type === 'customer.subscription.deleted') {
            $this->subscriptionCanceled($payload);
        }
    }

    public function subscriptionCreated($payload)
    {
        /** @var \App\Models\Account */
        $account = Cashier::findBillable($payload['customer']);
        $planName = getPlanNameFromProductId($payload['plan']['product']);

        $account->assignRole($planName);
    }

    public function subscriptionUpdated($payload)
    {
        /** @var \App\Models\Account */
        $account = Cashier::findBillable($payload['customer']);
        $planName = getPlanNameFromProductId($payload['plan']['product']);

        if ($payload['status'] === 'unpaid') {
            return $account->removeRole($planName);
        }
        if ($payload['status'] === 'active') {
            return $account->assignRole($planName);
        }

        // Check if user has changed they plan on Stripe Customer Portal
        $currentPlanName = $account->getCurrentPlan();
        if ($planName !== $currentPlanName) {
            $account->removeRole($currentPlanName);
            $account->assignRole($planName);
            return;
        }
    }

    public function subscriptionCanceled($payload)
    {
        /** @var \App\Models\Account */
        $account = Cashier::findBillable($payload['customer']);
        $planName = getPlanNameFromProductId($payload['plan']['product']);

        $account->removeRole($planName);
    }
}


function getPlanNameFromProductId(string $productId)
{
    return match ($productId) {
        'prod_MmkDJiG1yu9wDN' => 'basic-plan',
        'prod_Mnx08CzVvRtmAO' => 'standard-plan',
        'prod_MmkHXzANEWTqeS' => 'premium-plan',
    };
}
