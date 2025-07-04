<?php

namespace App\Application\Commands\PayCustomerOrder;

use Symfony\Component\Validator\Constraints as Assert;

class PayCustomerOrderCommand {
  public function __construct(
    #[Assert\NotBlank(message: "Customoer order id is required")]
    private readonly string $customerOrderId,

    #[Assert\NotBlank(message: "amount is required")]
    #[Assert\Positive(message: "Amount must not be negative")]
    private readonly int $amount
  ) {}

  public function getCustomerOrderId(): string {
    return $this->customerOrderId;
  }

  public function getAmount() {
    return $this->amount;
  }
}