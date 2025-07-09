<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\IDriverRepostiory;
use App\SalesDelivery\Domain\Entity\Driver;

class InMemoryDriverRepository implements IDriverRepostiory {
  public function __construct(private array $drivers = []) {}

  public function save($driver): void {
    array_push($this->drivers, $driver);
  }

  public function findById(string $id): ?Driver {
    foreach($this->drivers as $driver) {
      if ($driver->getId() === $id) {
        return $driver;
      }
    }

    return null;
  }
}
