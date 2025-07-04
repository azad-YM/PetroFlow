<?php

namespace App\Application\Commands\CreateCustomerOrder;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerOrderCommand {
  /**
   * @param OrderItem[] $items
   */
  public function __construct(
    #[Assert\NotBlank(message: 'Customer id is required')]
    private readonly string $customerId,

    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'At least one item is required')]
    private readonly array $items,

    #[Assert\NotBlank(message: "Deposit id is required")]
    private readonly string $depositId,
  ) {}

  public function getCustomerId(): string {
    return $this->customerId;
  }

  /**
   * @return OrderItem[]
   */
  public function getItems(): array {
    return $this->items;
  }

  public function getDepositId(): string {
    return $this->depositId;
  }
}