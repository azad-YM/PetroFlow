<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerRepository;
use App\SalesDelivery\Domain\Entity\Customer;

class InMemoryCustomerRepository implements ICustomerRepository {
  public function __construct(private array $customers = []) {}

  public function findById(string $id): ?Customer {
    foreach($this->customers as $customer) {
      if ($customer->getId() === $id) {
        return $customer;
      }
    }

    return null;
  }

  public function save($customer): void {
    array_push($this->customers, $customer);
  }
}
