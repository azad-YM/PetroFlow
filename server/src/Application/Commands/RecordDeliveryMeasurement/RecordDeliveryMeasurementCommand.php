<?php

namespace App\Application\Commands\RecordDeliveryMeasurement;

class RecordDeliveryMeasurementCommand {
  /**
   * @param string $deliveryId
   * @param MeasurementDTO measurements
   */
  public function __construct(
    private string $deliveryId,
    private array $measurements,
  ) {}

  public function getDeliveryId() {
    return $this->deliveryId;
  }

  public function getMeasurements() {
    return $this->measurements;
  }

}