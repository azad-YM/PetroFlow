<?php

namespace App\SalesDelivery\Domain\Model;

class AuthenticatedUser {
  public function __construct(private string $id) {}

  public function getId() {
    return $this->id;
  }
}