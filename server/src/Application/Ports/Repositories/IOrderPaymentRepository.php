<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\OrderPayment;

interface IOrderPaymentRepository {
  public function findById(string $id): ?OrderPayment;
  public function save(OrderPayment $orderPayment): void;
}