<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\CustomerDelivery;

interface ICustomerDeliveryRepository {
  public function findById(string $id): ?CustomerDelivery;
  public function save(CustomerDelivery $customerDelivery): void;
}