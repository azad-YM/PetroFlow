<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\CustomerOrder;

interface ICustomerOrderRepository {
  public function findById(string $id): ?CustomerOrder;
  public function save(CustomerOrder $customerOrder): void;
}