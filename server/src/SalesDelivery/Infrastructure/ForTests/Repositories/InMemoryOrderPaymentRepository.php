<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\IOrderPaymentRepository;
use App\SalesDelivery\Domain\Entity\OrderPayment;

class InMemoryOrderPaymentRepository implements IOrderPaymentRepository {
  private ?OrderPayment $orderPayment;

  public function findById(string $id): ?OrderPayment {
    return $this->orderPayment;
  }

  public function save(OrderPayment $payment): void {
    $this->orderPayment = $payment;
  }
}