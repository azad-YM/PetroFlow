<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\Driver;

interface IDriverRepostiory {
  public function save(Driver $driver): void;

  public function findById(string $id): ?Driver;
}