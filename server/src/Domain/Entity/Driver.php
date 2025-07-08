<?php

namespace App\Domain\Entity;

class Driver {
  public function __construct(private string $id) {}

  public function getId() {
    return $this->id;
  }
}