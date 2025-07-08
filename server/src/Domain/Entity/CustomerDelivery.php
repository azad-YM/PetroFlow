<?php

namespace App\Domain\Entity;

class CustomerDelivery {
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

  public function getDriver() {
    return $this->driver;
  }

  public function setDriver(Driver $driver) {
    $this->driver = $driver;
  }

  public function getVehicleId() {
    return $this->vehicleId;
  }

  public function setVehicleId(string $vehicleId) {
    $this->vehicleId = $vehicleId;
    return $this;
  }

  public function getVehicle() {
    return $this->vehicle;
  }

  public function setVehicle(Vehicle $vehicle) {
    $this->vehicle = $vehicle;
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

  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor(User $author) {
    $this->author = $author;
  }
}