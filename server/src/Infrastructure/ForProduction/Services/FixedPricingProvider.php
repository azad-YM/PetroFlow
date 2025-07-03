<?php

namespace App\Infrastructure\ForProduction\Services;

use App\Domain\Entity\CustomerOrder;
use App\Domain\Service\IPricingProvider;

class FixedPricingProvider implements IPricingProvider {
  public function isFullyPaid(int $paidAmount, CustomerOrder $order): bool {
    return $paidAmount === $order->getQuantity() * 1_000;
  }
}