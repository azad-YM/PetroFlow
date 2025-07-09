<?php

namespace App\SalesDelivery\Domain\Model;

class PaymentPlan {
  public function __construct(private int $totalAmount) {}

  public function getTotalAmount() {
    return $this->totalAmount;
  }
}