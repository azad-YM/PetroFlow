<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Services;

use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\Model\PaymentPlan;
use App\SalesDelivery\Domain\Service\IPricingProvider;

class FixedPricingProvider implements IPricingProvider {
  public function buildPaymentPlanFor(CustomerOrder $order): PaymentPlan {
    $quantity = array_reduce(
      $order->getItems(), 
      fn(int $acc, CustomerOrderItem $item) => $acc += $item->getQuantity(),
      0
    );
    return new PaymentPlan(totalAmount: $quantity * 1_000);
  }
}