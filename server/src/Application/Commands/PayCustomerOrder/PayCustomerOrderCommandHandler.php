<?php

namespace App\Application\Commands\PayCustomerOrder;

use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\IOrderPaymentRepository;
use App\Application\Ports\Services\IIdProvider;
use App\Domain\Entity\OrderPayment;
use App\Domain\ViewModel\IdViewModel;

class PayCustomerOrderCommandHandler {
  public function __construct(
    private IIdProvider $idProvider,
    private ICustomerOrderRepository $orderRepository,
    private IOrderPaymentRepository $paymentRepository,
  ) {}

  public function execute(string $orderId, int $amount) {
    $customerOrder = $this->orderRepository->findById($orderId);
    if (!$customerOrder) {
      throw new \Exception("Customer order not found");
    }

    $payment = new OrderPayment(
      id: $this->idProvider->getId(),
      amount: $amount
    );

    $customerOrder->setStatus("PRÊTE_LIVRAISON");
    $this->paymentRepository->save($payment);

    return new IdViewModel($this->idProvider->getId());
  }
}