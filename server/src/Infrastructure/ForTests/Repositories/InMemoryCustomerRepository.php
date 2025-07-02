<?php

namespace App\Infrastructure\ForTests\Repositories;

use App\Application\Ports\Repositories\ICustomerRepository;
use App\Domain\Entity\Customer;

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
