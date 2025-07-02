<?php

namespace App\Domain\Entity;

class CustomerOrder {
  private string $status = "EN_ATTENTE_PAIEMENT";

  private Customer $customer;
  private Deposit $deposit;
  private Product $product;

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

  public function setCustomerId(string $customerId) {
    $this->customerId = $customerId;
    return $this;
  }

  public function getProductId(): string {
    return $this->productId;
  }

  public function setProductId(string $productId) {
    $this->productId = $productId;
    return $this;
  }

  public function getDepositId(): string {
    return $this->depositId;
  }

  public function setDepositId(string $depositId) {
    $this->depositId = $depositId;
    return $this;
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

  public function getCustomer() {
    return $this->customer;
  }

  public function setCustomer(Customer $customer) {
    $this->customer = $customer;
    return $this;
  }

  public function getDeposit() {
    return $this->deposit;
  }

  public function setDeposit(Deposit $deposit) {
    $this->deposit = $deposit;
    return $this;
  }

  public function getProduct() {
    return $this->product;
  }

  public function setProduct(Product $product) {
    $this->product = $product;
    return $this;
  }
}