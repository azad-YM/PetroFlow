<?php

namespace App\Domain\Model;

interface IDeliveryMeasurement {
  public function getQuantity(): int;
}