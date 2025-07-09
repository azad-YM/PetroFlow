<?php

namespace App\Application\Commands\RecordDeliveryMeasurement;

use Symfony\Component\Validator\Constraints as Assert;

class RecordDeliveryMeasurementCommand {
  /**
   * @param string $deliveryId
   * @param MeasurementDTO[] $measurements
   */
  public function __construct(
    #[Assert\NotBlank(message: "Delivery can't be null")]
    private string $deliveryId,

    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'At least one item is required')]
    private array $measurements,
  ) {}

  public function getDeliveryId() {
    return $this->deliveryId;
  }

  public function getMeasurements() {
    return $this->measurements;
  }
}