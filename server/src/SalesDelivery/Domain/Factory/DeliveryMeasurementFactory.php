<?php

namespace App\SalesDelivery\Domain\Factory;

use App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement\MeasurementDTO as RecordDeliveryMeasurementMeasurementDTO;
use App\SalesDelivery\Domain\Entity\Measurement;
use App\SalesDelivery\Domain\Model\IDeliveryMeasurement;
use App\SalesDelivery\Domain\Model\ManuallyEnteredMeasurement;
use App\SalesDelivery\Domain\Model\MeasuredByCounter;

class DeliveryMeasurementFactory {
  public static function createFromDto(RecordDeliveryMeasurementMeasurementDTO $dto, string $authorId): IDeliveryMeasurement {
    if($dto->quantity === null) {
      return new MeasuredByCounter(
        $dto->start, 
        $dto->end, 
        $dto->tankId,
        $authorId
      );
    }

    return new ManuallyEnteredMeasurement(
      $dto->quantity, 
      $dto->tankId,
      $authorId
    );
  }

  public static function createFromEntity(Measurement $measurement): IDeliveryMeasurement {
    if($measurement->getQuantity() === null) {
      return new MeasuredByCounter(
        $measurement->getStart(), 
        $measurement->getEnd(), 
        $measurement->getTankId(),
        $measurement->getAuthorId()
      );
    }

    return new ManuallyEnteredMeasurement(
      $measurement->getQuantity(), 
      $measurement->getTankId(),
      $measurement->getAuthorId()
    );
  }
}