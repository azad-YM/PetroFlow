<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\Driver;

interface IDriverRepostiory {
  public function save(Driver $driver): void;

  public function findById(string $id): ?Driver;
}