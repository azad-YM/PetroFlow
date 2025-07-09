<?php

namespace App\Domain\Factory;

use App\Application\Commands\RecordDeliveryMeasurement\MeasurementDTO;
use App\Domain\Entity\Measurement;
use App\Domain\Model\IDeliveryMeasurement;
use App\Domain\Model\ManuallyEnteredMeasurement;
use App\Domain\Model\MeasuredByCounter;

class DeliveryMeasurementFactory {
  public static function createFromDto(MeasurementDTO $dto, string $authorId): IDeliveryMeasurement {
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