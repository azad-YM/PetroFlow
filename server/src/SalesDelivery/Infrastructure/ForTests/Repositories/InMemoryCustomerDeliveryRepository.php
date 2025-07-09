<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Domain\Entity\CustomerDelivery;

class InMemoryCustomerDeliveryRepository implements ICustomerDeliveryRepository {
  public function __construct(private array $deliveries = []) {}

  public function save(CustomerDelivery $delivery): void {
    array_push($this->deliveries, $delivery);
  }

  public function findById(string $id): ?CustomerDelivery {
    foreach($this->deliveries as $delivery) {
      if ($delivery->getId() === $id) {
        return $delivery;
      }
    }

    return null;
  }
}