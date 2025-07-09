<?php

namespace App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement;

use App\SalesDelivery\Application\Exception\NotFoundException;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Domain\Factory\DeliveryMeasurementFactory;
use App\SalesDelivery\Domain\ViewModel\IdViewModel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RecordDeliveryMeasurementCommandHandler {
  public function __construct(
    private ICustomerDeliveryRepository $deliveryRepository,
    private IAuthenticatedUserProvider $userProvider,
  ) {}

  public function execute(RecordDeliveryMeasurementCommand $command) {
    $delivery = $this->deliveryRepository->findById($command->getDeliveryId());
    if (!$delivery) {
      throw new NotFoundException("Delivery not found");
    }

    foreach($command->getMeasurements() as $dto) {
      $measurement = DeliveryMeasurementFactory::createFromDto(
        dto: $dto, 
        authorId: $this->userProvider->getUser()->getId()
      );
      $delivery->addMeasurement($measurement);
    }
    $this->deliveryRepository->save($delivery);
    return new IdViewModel($command->getDeliveryId());
  }

  public function __invoke(RecordDeliveryMeasurementCommand $command) {
    return $this->execute($command);
  }
}