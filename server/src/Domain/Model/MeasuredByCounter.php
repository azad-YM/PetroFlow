<?php

namespace App\Domain\Model;

class MeasuredByCounter implements IDeliveryMeasurement {
  public function __construct(private int $start, private int $end) {}

  public function getQuantity(): int {
    return $this->start - $this->end;
  }
}