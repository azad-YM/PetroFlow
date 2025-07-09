<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\OrderPayment;

interface IOrderPaymentRepository {
  public function findById(string $id): ?OrderPayment;
  public function save(OrderPayment $orderPayment): void;
}