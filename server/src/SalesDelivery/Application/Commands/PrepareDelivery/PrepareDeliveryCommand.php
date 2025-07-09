<?php

namespace App\SalesDelivery\Application\Commands\PrepareDelivery;

use Symfony\Component\Validator\Constraints as Assert;

class PrepareDeliveryCommand {
  public function __construct(
    #[Assert\NotBlank(message: "Customer order is requiered")]
    private string $customerOrderId, 

    #[Assert\NotBlank(message: "Driver is requiered")]
    private string $driverId, 

    #[Assert\NotBlank(message: "Vehicle is requiered")]
    private string $vehicleId,

    #[Assert\NotBlank(message: "delivery date is requiered")]
    private string $deliveryAt
  ) {}

  public function getCustomerOrderId() {
    return $this->customerOrderId;
  }

  public function getDriverId() {
    return $this->driverId;
  }

  public function getVehicleId() {
    return $this->vehicleId;
  }

  public function getDeliveryAt() {
    return $this->deliveryAt;
  }
}