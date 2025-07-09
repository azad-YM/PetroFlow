<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Services;

use App\SalesDelivery\Application\Ports\Services\IIdProvider;
use Symfony\Component\Uid\Uuid;

class RandomIdProvider implements IIdProvider {
  public function getId(): string {
    return Uuid::v7()->toString();
  }
}