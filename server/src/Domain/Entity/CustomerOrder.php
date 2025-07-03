<?php

namespace App\Domain\Entity;

use App\Domain\Service\IOrderPricing;
use App\Domain\Service\IOrderPricingProvider;
use App\Domain\Service\IPricingProvider;

class CustomerOrder {
  private string $paymentStatus = "NOT_PAYED";
  private string $deliveryStatus = "NOT_DELIVERED";
  private bool $deliveryAuthorized = false;


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

  public function isDeliveryAuthorized(): bool {
    return $this->deliveryAuthorized;
  }

  public function registerPayment(int $paidAmount, IPricingProvider $priceProvider) {
    $isPaid = $priceProvider->isFullyPaid($paidAmount, $this);
    if ($isPaid) {
      $this->deliveryAuthorized = true;
      $this->paymentStatus = 'PAYED';
    } else {
      $this->deliveryAuthorized = false;
      $this->paymentStatus = 'PARTIALLY_PAYED';
    }
  }
}