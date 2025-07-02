<?php

namespace App\Infrastructure\ForTests\Repositories;

use App\Application\Ports\Repositories\IOrderPaymentRepository;
use App\Domain\Entity\OrderPayment;

class InMemoryOrderPaymentRepository implements IOrderPaymentRepository {
  private ?OrderPayment $orderPayment;

  public function findById(string $id): ?OrderPayment {
    return $this->orderPayment;
  }

  public function save(OrderPayment $payment): void {
    $this->orderPayment = $payment;
  }
}