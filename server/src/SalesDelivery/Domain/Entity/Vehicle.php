<?php

namespace App\SalesDelivery\Domain\Entity;

class Vehicle {
  public function __construct(private string $id) {}

  public function getId() {
    return $this->id;
  }
}