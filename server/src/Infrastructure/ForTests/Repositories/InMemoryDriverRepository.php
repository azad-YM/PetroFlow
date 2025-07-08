<?php

namespace App\Infrastructure\ForTests\Repositories;

use App\Application\Ports\Repositories\IDriverRepostiory;
use App\Domain\Entity\Driver;

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
