<?php

namespace App\Domain\Service;

use App\Domain\Entity\CustomerOrder;

interface IPricingProvider {
  public function isFullyPaid(int $paidAmount, CustomerOrder $order): bool;
}