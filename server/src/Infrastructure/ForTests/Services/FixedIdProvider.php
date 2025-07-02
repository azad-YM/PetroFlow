<?php

namespace App\Infrastructure\ForTests\Services;

use App\Application\Ports\Services\IIdProvider;

class FixedIdProvider implements IIdProvider {

  public function __construct(private string $id) {}

  public function getId(): string {
    return $this->id;
  }
}