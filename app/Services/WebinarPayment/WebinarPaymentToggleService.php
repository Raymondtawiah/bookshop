<?php

namespace App\Services\WebinarPayment;

use App\Models\WebinarSession;

class WebinarPaymentToggleService implements WebinarPaymentToggleServiceInterface
{
    public function isPaymentEnabled(WebinarSession $webinar): bool
    {
        return (bool) $webinar->payment_enabled;
    }

    public function enablePayment(WebinarSession $webinar): bool
    {
        return $webinar->update(['payment_enabled' => true]);
    }

    public function disablePayment(WebinarSession $webinar): bool
    {
        return $webinar->update(['payment_enabled' => false]);
    }

    public function toggle(WebinarSession $webinar): bool
    {
        $newState = ! $this->isPaymentEnabled($webinar);

        return $webinar->update(['payment_enabled' => $newState]);
    }
}
