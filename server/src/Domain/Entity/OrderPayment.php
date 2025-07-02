<?php

namespace App\Domain\Entity;

class OrderPayment {
  public function __construct(
    private string $id,
    private int $amount = 0,
  ) {}

  public function getId() {
    return $this->id;
  }

  public function getAmount() {
    return $this->amount;
  }
}