<?php

namespace App\Domain\Factory;

use App\Application\Commands\RecordDeliveryMeasurement\MeasurementDTO;
use App\Domain\Model\IDeliveryMeasurement;
use App\Domain\Model\ManuallyEnteredMeasurement;
use App\Domain\Model\MeasuredByCounter;
use App\Domain\Model\Measurement;

class DeliveryMeasurementFactory {
  public static function createFromDto(MeasurementDTO $dto): IDeliveryMeasurement {
    if($dto->quantity === null) {
      return new MeasuredByCounter($dto->start, $dto->end);
    }

    return new ManuallyEnteredMeasurement($dto->quantity);
  }
}