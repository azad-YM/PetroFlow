<?php

namespace App\Application\Commands\CreateCustomerOrder;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerOrderCommand {
  public function __construct(
    #[Assert\NotBlank(message: 'Customer id is required')]
    private readonly string $customerId,

    #[Assert\NotBlank(message: "Product id is required")]
    private readonly string $productId,

    #[Assert\NotBlank(message: "Deposit id is required")]
    private readonly string $depositId,

    #[Assert\NotBlank()]
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