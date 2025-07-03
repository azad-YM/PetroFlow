<?php

namespace App\Domain\Entity;

class CustomerOrder {
  private string $paymentStatus = "NOT_PAYED";
  private string $deliveryStatus = "NOT_DELIVERED";


  private Customer $customer;
  private Deposit $deposit;
  private Product $product;
  private User $author;

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

  public function getPaymentStatus() {
    return $this->paymentStatus;
  }

  public function setPaymentStatus(string $status) {
    $this->paymentStatus = $status;
  }

  public function getDeliveryStatus() {
    return $this->deliveryStatus;
  }

  public function setDeliveryStatus(string $status) {
    $this->deliveryStatus = $status;
  }

  public function getAuthorId() {
    return $this->authorId;
  }

  public function setAuthorId(string $authorId) {
    $this->authorId = $authorId;
    return $this;
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

  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor(User $author) {
    $this->author = $author;
    return $this;
  }
}