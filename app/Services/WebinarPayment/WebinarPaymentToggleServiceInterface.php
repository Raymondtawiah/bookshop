<?php

namespace App\Services\WebinarPayment;

use App\Models\WebinarSession;

interface WebinarPaymentToggleServiceInterface
{
    public function isPaymentEnabled(WebinarSession $webinar): bool;

    public function enablePayment(WebinarSession $webinar): bool;

    public function disablePayment(WebinarSession $webinar): bool;

    public function toggle(WebinarSession $webinar): bool;
}
