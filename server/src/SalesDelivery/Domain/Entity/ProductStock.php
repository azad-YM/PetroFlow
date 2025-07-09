<?php

namespace App\SalesDelivery\Domain\Entity;

class ProductStock {
  private int $allocatedQuantity = 0;

  public function __construct(private Product $product, private int $quantity = 0) {}

  public function getProduct(): Product {
    return $this->product;
  }

  public function getQuantity() {
    return $this->quantity - $this->allocatedQuantity;
  }

  public function isAvailable(int $quantity): bool {
    return $this->quantity > $quantity;
  }

  public function allocate(int $quantity): void {
    if ($this->isAvailable($quantity)) {
      $this->allocatedQuantity += $quantity;
    }
  }

  public function getAllocatedQuantity(): int {
    return $this->allocatedQuantity;
  }
}