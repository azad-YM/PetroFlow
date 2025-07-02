<?php

namespace App\Domain\Entity;

class Customer {
  public function __construct(private string $id) {}

  public function getId(): string {
    return $this->id;
  }
}