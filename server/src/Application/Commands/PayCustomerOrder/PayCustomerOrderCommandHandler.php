<?php

namespace App\Application\Commands\PayCustomerOrder;

use App\Application\Exception\NotFoundException;
use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\IOrderPaymentRepository;
use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Application\Ports\Services\IIdProvider;
use App\Domain\Entity\OrderPayment;
use App\Domain\Service\IPricingProvider;
use App\Domain\ViewModel\IdViewModel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PayCustomerOrderCommandHandler {
  public function __construct(
    private IIdProvider $idProvider,
    private ICustomerOrderRepository $orderRepository,
    private IOrderPaymentRepository $paymentRepository,
    private IPricingProvider  $pricingProvider,
    private IAuthenticatedUserProvider $authenticatedUserProvider,
  ) {}

  public function execute(PayCustomerOrderCommand $command) {
    $customerOrder = $this->orderRepository->findById($command->getCustomerOrderId());
    if (!$customerOrder) {
      throw new NotFoundException("Customer order not found");
    }

    $payment = new OrderPayment(
      id: $this->idProvider->getId(),
      amount: $command->getAmount(),
      customerOrderId: $customerOrder->getId(),
      authorId: $this->authenticatedUserProvider->getUser()->getId(),
    );

    $this->paymentRepository->save($payment);
    $customerOrder->registerPayment(
      paidAmount: $command->getAmount(), 
      priceProvider: $this->pricingProvider
    );

    return new IdViewModel($payment->getId());
  }

  public function __invoke(PayCustomerOrderCommand $command) {
    return $this->execute($command);
  }
}