<?php

namespace App\SalesDelivery\Domain\Entity;

use App\SalesDelivery\Domain\Model\IDeliveryMeasurement;

class CustomerDelivery {
  /** @var IDeliveryMeasurement[] */
  private array $measurements = [];

  private $collectionMeasurementsForDoctrine;
  private Driver $driver;
  private Vehicle $vehicle;
  private User $author;

  public function __construct(
    private string $id, 
    private string $orderId,
    private string $driverId,
    private string $vehicleId,
    private string $deliveryAt,
    private string $authorId,
  ) {}

  public function getId() {
    return $this->id;
  }

  public function getOrderId() {
    return $this->orderId;
  }

  public function getDriverId() {
    return $this->driverId;
  }

  public function setDriverId(string $driverId) {
    $this->driverId = $driverId;
    return $this;
  }

  public function getVehicleId() {
    return $this->vehicleId;
  }

  public function setVehicleId(string $vehicleId) {
    $this->vehicleId = $vehicleId;
    return $this;
  }

  public function getDeliveryAt() {
    return $this->deliveryAt;
  }

  public function getAuthorId(): string {
    return $this->authorId;
  }

  public function setAuthorId(string $authorId) {
    $this->authorId = $authorId;
    return $this;
  }

  public function getDelivredQuantity() {
    return array_sum(array_map(
      fn(IDeliveryMeasurement $m) => $m->getQuantity(),
      $this->measurements
    ));
  }

  public function getMeasurements() {
    return $this->measurements;
  }

  public function addMeasurement(IDeliveryMeasurement $measurement) {
    array_push($this->measurements, $measurement);
  }




   public function setCollectionMeasurementsForDoctrine($items) {
    $this->collectionMeasurementsForDoctrine = $items;
    return $this;
  }

  public function getCollectionMeasurementsForDoctrine() {
    return $this->collectionMeasurementsForDoctrine;
  }

  public function getDriver() {
    return $this->driver;
  }

  public function setDriver(Driver $driver) {
    $this->driver = $driver;
  }

  public function getVehicle() {
    return $this->vehicle;
  }

  public function setVehicle(Vehicle $vehicle) {
    $this->vehicle = $vehicle;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor(User $author) {
    $this->author = $author;
  }
}