<?php

namespace App\Domain\Model;

class Measurement {
  public function __construct(
    public ?int $start = null,
    public ?int $end = null,
    public ?int $quantity = null,
  ) {}
}