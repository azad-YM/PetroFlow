<?php

namespace App\Domain\Entity;

class CustomerOrder {
  private string $status = "EN_ATTENTE_PAIEMENT";

  public function __construct(
    private string $id, 
    private int $quantity,
    private string $customerId,
    private string $productId,
    private string $depositId,
    private string $authorId,
  ) {}

  public function getId(): string {
    return $this->id;
  }

  public function getCustomerId(): string {
    return $this->customerId;
  }

  public function getProductId(): string {
    return $this->productId;
  }

  public function getDepositId(): string {
    return $this->depositId;
  }

  public function getQuantity(): int {
    return $this->quantity;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus(string $status) {
    $this->status = $status;
  }

  public function getAuthorId() {
    return $this->authorId;
  }
}