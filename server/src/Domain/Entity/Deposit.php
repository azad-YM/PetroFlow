<?php

namespace App\Domain\Entity;

class Deposit {
  public function __construct(private string $id, private array $stocks = []) {}

  public function getId(): string {
    return $this->id;
  }

  public function getStockForProduct(string $productId): ?ProductStock {
    foreach($this->stocks as $stock) {
      if($stock->getProduct()->getId() === $productId) {
        return $stock;
      }
    }

    return null;
  }

  public function allocateStockFor(string $productId, int $quantity): void {
    if (!$this->hasProduct($productId)) {
      throw new \Exception("Product not found");
    }

    if (!$this->canFulfillOrder($productId, $quantity)) {
      throw new \Exception('Stock not available');
    }

    $stock = $this->getStockForProduct($productId);
    $stock->allocate($quantity);
  }

  public function canFulfillOrder(string $productId, int $quantity): bool {
    $stock = $this->getStockForProduct($productId);
    $isAvailable = $stock && $stock->isAvailable($quantity);
    return $isAvailable ? true : false;
  }

  public function hasProduct(string $productId): bool
  {
    foreach ($this->stocks as $stock) {
      if ($stock->getProduct()->getId() === $productId) {
        return true;
      }
    }
    return false;
  }

  public function getAllocatedStockFor(string $productId): int {
    return $this->getStockForProduct($productId)?->getAllocatedQuantity();
  }
}