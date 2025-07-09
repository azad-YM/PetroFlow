<?php

namespace App\SalesDelivery\Application\Commands\PayCustomerOrder;

use App\SalesDelivery\Application\Exception\NotFoundException;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Application\Ports\Repositories\IOrderPaymentRepository;
use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Application\Ports\Services\IIdProvider;
use App\SalesDelivery\Domain\Entity\OrderPayment;
use App\SalesDelivery\Domain\Service\IPricingProvider;
use App\SalesDelivery\Domain\ViewModel\IdViewModel;
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
    $paymentPlan = $this->pricingProvider->buildPaymentPlanFor($customerOrder);

    $customerOrder->registerPayment(
      paidAmount: $command->getAmount(), 
      plan: $paymentPlan
    );

    return new IdViewModel($payment->getId());
  }

  public function __invoke(PayCustomerOrderCommand $command) {
    return $this->execute($command);
  }
}