<?php

namespace App\SalesDelivery\Domain\Entity;

class Product {
  public function __construct(private string $id) {}

  public function getId(): string {
    return $this->id;
  }
}