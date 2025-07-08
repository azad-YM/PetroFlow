<?php

namespace App\Application\Commands\PrepareDelivery;

use App\Application\Exception\NotFoundException;
use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\IDriverRepostiory;
use App\Application\Ports\Repositories\IVehicleRepostiory;
use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Application\Ports\Services\IIdProvider;
use App\Domain\Entity\CustomerDelivery;
use App\Domain\ViewModel\IdViewModel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PrepareDeliveryCommandHandler {
  public function __construct(
    private IIdProvider $idProvider,
    private ICustomerOrderRepository $customerOrderRepository,
    private IDriverRepostiory $driverRepository,
    private IVehicleRepostiory $vehicleRepository,
    private IAuthenticatedUserProvider $authenticatedUserProvider,
    private ICustomerDeliveryRepository $customerDeliveryRepository
  ) {}

  public function execute(PrepareDeliveryCommand $command) {
    $driver = $this->driverRepository->findById($command->getDriverId());
    if (!$driver) {
      throw new NotFoundException("Driver not found");
    }

    $vehicle = $this->vehicleRepository->findById($command->getVehicleId());
    if (!$vehicle) {
      throw new NotFoundException("Vehicle not found");
    }

    $customerOrder = $this->customerOrderRepository->findById($command->getCustomerOrderId());
    if (!$customerOrder) {
      throw new NotFoundException("Customer order not found");
    }

    if (!$customerOrder->isDeliveryAuthorized()) {
      throw new \Exception("Order unauthorized for delivery");
    }

    $customerDelivery = new CustomerDelivery(
      $this->idProvider->getId(),
      $command->getCustomerOrderId(),
      $command->getDriverId(),
      $command->getVehicleId(),
      $command->getDeliveryAt(),
      $this->authenticatedUserProvider->getUser()->getId()
    );

    $customerOrder->setDeliveryStatus('INPROGRESS');
    $this->customerDeliveryRepository->save($customerDelivery);
    return new IdViewModel($customerDelivery->getId());
  }

  public function __invoke(PrepareDeliveryCommand $command) {
    return $this->execute($command);
  }
}