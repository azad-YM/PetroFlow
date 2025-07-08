<?php

namespace App\Domain\Entity;

use App\Domain\Model\PaymentPlan;

class CustomerOrder {
  private string $paymentStatus = "NOT_PAYED";
  private string $deliveryStatus = "NOT_DELIVERED";
  private bool $deliveryAuthorized = false;


  private Customer $customer;
  private Deposit $deposit;
  private User $author;
  private $collectionItemsForDoctrine;

  public function __construct(
    private string $id, 
    /** @var CustomerOrderItem[] */
    private array $items,
    private string $customerId,
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

    /** @var CustomerOrderItem[] */
  public function getItems() {
    return $this->items;
  }

  public function setItems(array $items) {
    $this->items = $items;
    return $this;
  }

  public function setCollectionItemsForDoctrine($items) {
    $this->collectionItemsForDoctrine = $items;
    return $this;
  }

  public function getCollectionItemsForDoctrine() {
    return $this->collectionItemsForDoctrine;
  }

  public function getDepositId(): string {
    return $this->depositId;
  }

  public function setDepositId(string $depositId) {
    $this->depositId = $depositId;
    return $this;
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

  public function authorizeDelivery() {
    $this->deliveryAuthorized = true;
  }

  public function registerPayment(int $paidAmount, PaymentPlan $plan) {
    if ($paidAmount >= $plan->getTotalAmount()) {
      $this->deliveryAuthorized = true;
      $this->paymentStatus = 'PAYED';
      return;
    } 
  
    if ($plan->getTotalAmount() > $paidAmount) {
      $this->deliveryAuthorized = false;
      $this->paymentStatus = 'PARTIALLY_PAYED';
    }
  }
}