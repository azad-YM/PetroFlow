<?php

namespace App\Application\Commands\PayCustomerOrder;

class PayCustomerOrderCommand {
  public function __construct(
    private readonly string $customerOrderId,
    private readonly int $amount
  ) {}

  public function getCustomerOrderId(): string {
    return $this->customerOrderId;
  }

  public function getAmount() {
    return $this->amount;
  }
}