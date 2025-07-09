<?php

namespace App\SalesDelivery\Domain\Model;

class MeasuredByCounter implements IDeliveryMeasurement {
  public function __construct(
    private int $start, 
    private int $end,
    private string $tankId,
    private string $authorId
  ) {}

  public function getQuantity(): int {
    return $this->start - $this->end;
  }

  public function getStart() {
    return $this->start;
  }

  public function getEnd() {
    return $this->end;
  }

  public function getTankId(): string {
    return $this->tankId;
  }

  public function getAuthorId(): string {
    return $this->authorId;
  }
}