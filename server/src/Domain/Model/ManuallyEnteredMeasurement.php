<?php

namespace App\Domain\Model;

class ManuallyEnteredMeasurement implements IDeliveryMeasurement {
  public function __construct(private int $quantity) {}

  public function getQuantity(): int {
    return $this->quantity;
  }
}