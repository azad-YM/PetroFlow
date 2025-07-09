<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\CustomerDelivery;

interface ICustomerDeliveryRepository {
  public function findById(string $id): ?CustomerDelivery;
  public function save(CustomerDelivery $customerDelivery): void;
}