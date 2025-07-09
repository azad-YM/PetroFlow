<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\Customer;

interface ICustomerRepository {
  public function findById(string $id): ?Customer;
  public function save(Customer $customer): void;
}