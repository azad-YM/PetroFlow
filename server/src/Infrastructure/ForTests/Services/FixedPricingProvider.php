<?php

namespace App\Infrastructure\ForTests\Services;

use App\Domain\Entity\CustomerOrder;
use App\Domain\Entity\CustomerOrderItem;
use App\Domain\Model\PaymentPlan;
use App\Domain\Service\IPricingProvider;

class FixedPricingProvider implements IPricingProvider {
  public function buildPaymentPlanFor(CustomerOrder $order): PaymentPlan {
    $quantity = array_reduce(
      $order->getItems(), 
      fn(int $acc, CustomerOrderItem $item) => $acc += $item->getQuantity(),
      0
    );
    return new PaymentPlan($quantity * 1_000);
  }
}