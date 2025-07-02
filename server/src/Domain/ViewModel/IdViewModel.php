<?php

namespace App\Domain\ViewModel;

class IdViewModel {
  public function __construct(private string $id) {}

  public function getId(): string {
    return $this->id;
  }
}