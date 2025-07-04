<?php

namespace App\Domain\Entity;

class CustomerOrderItem {
  private CustomerOrder $order; //Utitliser spécifiquement pour doctrine

  public function __construct(
    private string $id, 
    private string $productId, 
    private int $quantity,
  ) {}

  public function getProductId() {
    return $this->productId;
  }

  public function getQuantity() {
    return $this->quantity;
  }

  // A ne jamais utiliser en déhors de doctrine
  public function getOrder() {
    return $this->order;
  }

  public function setOrder(CustomerOrder $order) {
    $this->order = $order;
    return $this;
  }
}