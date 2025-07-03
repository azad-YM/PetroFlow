<?php

namespace App\Application\Commands\CreateCustomerOrder;

use App\Application\Exception\NotFoundException;
use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\ICustomerRepository;
use App\Application\Ports\Repositories\IDepositRepository;
use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Application\Ports\Services\IIdProvider;
use App\Domain\Entity\CustomerOrder;
use App\Domain\ViewModel\IdViewModel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateCustomerOrderCommandHandler {
  public function __construct(
    private IIdProvider $idProvider,
    private ICustomerOrderRepository $customerOrderRepository,
    private ICustomerRepository $customerRespository,
    private IDepositRepository $depositRepository,
    private IAuthenticatedUserProvider $userProvider
  ) {}

  public function execute(CreateCustomerOrderCommand $command) {
    $customer = $this->customerRespository->findById($command->getCustomerId());
    if (!$customer) {
      throw new NotFoundException("Customer not found");
    }
    
    $deposit = $this->depositRepository->findById($command->getDepositId());
    if(!$deposit) {
      throw new NotFoundException('Deposit not found');
    }

    $deposit->allocateStockFor($command->getProductId(), $command->getQuantity());

    $customerOrder = new CustomerOrder(
      id: $this->idProvider->getId(),
      quantity: $command->getQuantity(),
      customerId: $customer->getId(),
      productId: $command->getProductId(),
      depositId: $deposit->getId(),
      authorId: $this->userProvider->getUser()->getId()
    );

    $this->customerOrderRepository->save($customerOrder);
    return new IdViewModel($customerOrder->getId());
  }

  public function __invoke(CreateCustomerOrderCommand $command) {
    return $this->execute($command);
  }
}
