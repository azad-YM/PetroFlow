<?php

namespace App\SalesDelivery\Application\Commands\CreateCustomerOrder;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderItem
{
  public function __construct(
    #[Assert\NotBlank(message: 'Product id is required')]
    private readonly string $productId,

    #[Assert\GreaterThan(value: 0, message: 'Quantity must be greater than zero')]
    private readonly int $quantity
  ) {}

  public function getProductId(): string {
    return $this->productId;
  }

  public function getQuantity(): int {
    return $this->quantity;
  }
}