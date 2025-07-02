<?php

namespace App\Application\Commands\CreateCustomerOrder;

class CreateCustomerOrderCommand {
  public function __construct(
    private readonly string $customerId,
    private readonly string $productId,
    private readonly string $depositId,
    private readonly int $quantity = 0,
  ) {}

  public function getCustomerId(): string {
    return $this->customerId;
  }

  public function getProductId(): string {
    return $this->productId;
  }

  public function getDepositId(): string {
    return $this->depositId;
  }

  public function getQuantity(): int {
    return $this->quantity;
  }
}