<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\IVehicleRepostiory;
use App\SalesDelivery\Domain\Entity\Vehicle;

class InMemoryVehicleRepository implements IVehicleRepostiory {
  public function __construct(private array $vehicles = []) {}

  public function save($vehicle): void {
    array_push($this->vehicles, $vehicle);
  }

  public function findById(string $id): ?Vehicle {
    foreach($this->vehicles as $vehicle) {
      if ($vehicle->getId() === $id) {
        return $vehicle;
      }
    }

    return null;
  }
}
