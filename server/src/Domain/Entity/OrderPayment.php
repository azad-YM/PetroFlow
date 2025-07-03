<?php

namespace App\Domain\Entity;

class OrderPayment {
  private CustomerOrder $customerOrder;
  private User $author;

  public function __construct(
    private string $id,
    private int $amount = 0,
    private string $customerOrderId,
    private string $authorId
  ) {}

  public function getId() {
    return $this->id;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function getCustomerOrderId(): string {
    return $this->customerOrderId;
  }

  public function setCustomerOrderId(string $id) {
    $this->customerOrderId = $id;
  }

  public function getCustomerOrder() {
    return $this->customerOrder;
  }

  public function setCustomerOrder(CustomerOrder $customerOrder) {
    $this->customerOrder = $customerOrder;
  }

  public function getAuthorId() {
    return $this->authorId;
  }

  public function setAuthorId(string $id) {
    $this->authorId = $id;
  }

  public function getAuthor(): User {
    return $this->author;
  }

  public function setAuthor(User $author) {
    $this->author = $author;
  }
}