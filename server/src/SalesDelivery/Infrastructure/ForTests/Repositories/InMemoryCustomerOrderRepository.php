<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Domain\Entity\CustomerOrder;

class InMemoryCustomerOrderRepository implements ICustomerOrderRepository {
  public function __construct(private array $orders = []) {}

  public function save($customerOrder): void {
    array_push($this->orders, $customerOrder);
  }

  public function findById(string $id): ?CustomerOrder {
    foreach($this->orders as $order) {
      if ($order->getId() === $id) {
        return $order;
      }
    }

    return null;
  }
}