<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\Vehicle;

interface IVehicleRepostiory {
  public function save(Vehicle $driver): void;

  public function findById(string $id): ?Vehicle;
}