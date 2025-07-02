<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\CustomerOrder;

interface ICustomerOrderRepository {
  public function findById(string $id): ?CustomerOrder;
  public function save(CustomerOrder $customerOrder): void;
}