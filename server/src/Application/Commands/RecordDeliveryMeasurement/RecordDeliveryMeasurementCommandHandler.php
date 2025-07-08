<?php

namespace App\Application\Commands\RecordDeliveryMeasurement;

use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Domain\Factory\DeliveryMeasurementFactory;
use App\Domain\Model\Measurement;
use App\Domain\ViewModel\IdViewModel;

class RecordDeliveryMeasurementCommandHandler {
  public function __construct(
    private ICustomerDeliveryRepository $deliveryRepository,
  ) {}

  public function execute(RecordDeliveryMeasurementCommand $command) {
    $delivery = $this->deliveryRepository->findById($command->getDeliveryId());
    if (!$delivery) {
      throw new \Exception("Delivery not found");
    }

    foreach($command->getMeasurements() as $dto) {
      $measurement = DeliveryMeasurementFactory::createFromDto($dto);
      $delivery->addMeasurement($measurement);
    }

    return new IdViewModel($command->getDeliveryId());
  }
}