<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\Vehicle;

interface IVehicleRepostiory {
  public function save(Vehicle $driver): void;

  public function findById(string $id): ?Vehicle;
}