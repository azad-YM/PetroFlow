<?php

namespace App\SalesDelivery\Domain\Model;

interface IDeliveryMeasurement {
  public function getQuantity(): int;
  public function getTankId(): string;
  public function getAuthorId(): string;
}