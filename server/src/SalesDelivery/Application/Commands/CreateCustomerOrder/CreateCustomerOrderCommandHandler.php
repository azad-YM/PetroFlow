<?php

namespace App\SalesDelivery\Application\Commands\CreateCustomerOrder;

use App\SalesDelivery\Application\Exception\NotFoundException;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerRepository;
use App\SalesDelivery\Application\Ports\Repositories\IDepositRepository;
use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Application\Ports\Services\IIdProvider;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\ViewModel\IdViewModel;
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
    $items = [];
    foreach($command->getItems() as $item) {
      $deposit->allocateStockFor($item->getProductId(), $item->getQuantity());
      $items[] = new CustomerOrderItem(
        $this->idProvider->getId(),
        $item->getProductId(), 
        $item->getQuantity()
      );
    }

    $customerOrder = new CustomerOrder(
      id: $this->idProvider->getId(),
      items: $items,
      customerId: $customer->getId(),
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
