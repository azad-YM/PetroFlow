<?php

namespace App\Domain\Service;

use App\Domain\Entity\CustomerOrder;
use App\Domain\Model\PaymentPlan;

interface IPricingProvider {
  public function buildPaymentPlanFor(CustomerOrder $order): PaymentPlan;
}