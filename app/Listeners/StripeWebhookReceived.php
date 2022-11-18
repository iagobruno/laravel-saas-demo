<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
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

        if ($type === 'customer.subscription.created') {
            $this->subscriptionCreated($payload, $event->payload);
        } //
        else if ($type === 'customer.subscription.updated') {
            $this->subscriptionUpdated($payload, $event->payload);
        } //
        else if ($type === 'customer.subscription.deleted') {
            $this->subscriptionCanceled($payload, $event->payload);
        } //
        else if ($type === 'product.updated') {
            $this->productUpdatedOnStripe($payload, $event->payload);
        }
    }

    public function subscriptionCreated($data)
    {
        /** @var \App\Models\Account */
        $account = Cashier::findBillable($data['customer']);
        $planName = getPlanNameFromProductId($data['plan']['product']);

        $account->assignRole($planName);
    }

    public function subscriptionUpdated($data, $payload)
    {
        // info('payload' . json_encode($payload, JSON_PRETTY_PRINT));

        /** @var \App\Models\Account */
        $account = Cashier::findBillable($data['customer']);
        $planName = getPlanNameFromProductId($data['plan']['product']);

        $subscriptionStatusChanged = array_key_exists('status', $payload['data']['previous_attributes']);
        if ($subscriptionStatusChanged) {
            if ($data['status'] === 'active') {
                $account->assignRole($planName);
            } else {
                $account->removeRole($planName);
            }
        }

        $userChangedHisPlan = array_key_exists('plan', $payload['data']['previous_attributes']);
        if ($userChangedHisPlan) {
            $account
                ->removeRole($account->getCurrentPlan())
                ->assignRole($planName);
        }
    }

    public function subscriptionCanceled($data)
    {
        /** @var \App\Models\Account */
        $account = Cashier::findBillable($data['customer']);
        $planName = getPlanNameFromProductId($data['plan']['product']);

        $account->removeRole($planName);
    }

    public function productUpdatedOnStripe($data)
    {
        Cache::forget('plans-from-stripe');
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
