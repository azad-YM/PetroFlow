<?php

namespace App\SalesDelivery\Domain\Model;

class ManuallyEnteredMeasurement implements IDeliveryMeasurement {
  public function __construct(
    private int $quantity, 
    private string $tankId,
    private string $authorId
  ) {}

  public function getQuantity(): int {
    return $this->quantity;
  }

  public function getTankId(): string {
    return $this->tankId;
  }

  public function getAuthorId(): string {
    return $this->authorId;
  }
}