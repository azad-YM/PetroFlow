<?php

namespace App\SalesDelivery\Domain\Service;

use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Model\PaymentPlan;

interface IPricingProvider {
  public function buildPaymentPlanFor(CustomerOrder $order): PaymentPlan;
}