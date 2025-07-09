<?php

namespace App\Domain\Entity;

class Measurement {
  private CustomerDelivery $delivery; //Utitliser spécifiquement pour doctrine
  private int $id;

  public function __construct(
    private string $deliveryId,
    private string $tankId,
    private string $authorId,
    private ?int $start = null,
    private ?int $end = null,
    private ?int $quantity = null,
  ) {}

  public function getId() {
    return $this->id;
  }

  public function getDeliveryId() {
    return $this->deliveryId;
  }

  public function getTankId() {
    return $this->tankId;
  }

  public function getAuthorId() {
    return $this->authorId;
  }

  public function getStart() {
    return $this->start;
  }

  public function getEnd() {
    return $this->end;
  }

  public function getQuantity() {
    return $this->quantity;
  }


  // A ne jamais utiliser en déhors de doctrine
  public function getDelivery() {
    return $this->delivery;
  }

  public function setDelivery(CustomerDelivery $delivery) {
    $this->delivery = $delivery;
    return $this;
  }
}