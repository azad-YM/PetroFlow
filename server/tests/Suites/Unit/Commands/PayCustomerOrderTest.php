<?php

namespace App\Tests\Suites\Unit\Commands;

use App\Application\Commands\PayCustomerOrder\PayCustomerOrderCommandHandler;
use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\IOrderPaymentRepository;
use App\Domain\Entity\CustomerOrder;
use App\Infrastructure\ForTests\Repositories\InMemoryCustomerOrderRepository;
use App\Infrastructure\ForTests\Repositories\InMemoryOrderPaymentRepository;
use App\Infrastructure\ForTests\Services\FixedIdProvider;
use PHPUnit\Framework\TestCase;

class PayCustomerOrderTest extends TestCase {
  private PayCustomerOrderCommandHandler $commandHandler;

  private ICustomerOrderRepository $orderRepository;

  private IOrderPaymentRepository $paymentRepository;

  public function setUp(): void {
    parent::setUp();
    $orders = [
      new CustomerOrder("customer-order-id", 2_000, "customer-id", "product-id", "deposit-id", "author-id"),
    ];

    $this->orderRepository = new InMemoryCustomerOrderRepository($orders);
    $this->paymentRepository = new InMemoryOrderPaymentRepository();
    $this->commandHandler = new PayCustomerOrderCommandHandler(
      new FixedIdProvider("payment-id"),
      $this->orderRepository,
      $this->paymentRepository
    );
  }

  public function test_happyPath_ShouldPayCustomerOrder() {
    $customerOrder = $this->orderRepository->findById("customer-order-id");
    $response = $this->commandHandler->execute(orderId: "customer-order-id", amount: 2_000_000);
    $payment = $this->paymentRepository->findById($response->getId());

    $this->assertEquals("payment-id", $response->getId());
    $this->assertNotNull($payment);
    $this->assertEquals(2_000_000, $payment->getAmount());
    $this->assertEquals("PRÊTE_LIVRAISON", $customerOrder?->getStatus());
  }

  public function test_WhenCustomerOrderNotFound_ShouldThrow() {
    $this->expectExceptionMessage("Customer order not found");
    $this->commandHandler->execute(orderId: "not-found-id", amount: 0);
  }
}