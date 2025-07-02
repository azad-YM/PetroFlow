<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\Customer;

interface ICustomerRepository {
  public function findById(string $id): ?Customer;
}