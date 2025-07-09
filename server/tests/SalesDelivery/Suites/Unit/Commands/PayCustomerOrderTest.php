<?php

namespace App\Tests\SalesDelivery\Suites\Unit\Commands;

use App\SalesDelivery\Application\Commands\PayCustomerOrder\PayCustomerOrderCommand;
use App\SalesDelivery\Application\Commands\PayCustomerOrder\PayCustomerOrderCommandHandler;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Application\Ports\Repositories\IOrderPaymentRepository;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\Model\AuthenticatedUser;
use App\SalesDelivery\Domain\Service\IPricingProvider;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryCustomerOrderRepository;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryOrderPaymentRepository;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedAuthenticatedUserProvider;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedIdProvider;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedPricingProvider;
use PHPUnit\Framework\TestCase;

class PayCustomerOrderTest extends TestCase {
  private PayCustomerOrderCommandHandler $commandHandler;

  private ICustomerOrderRepository $orderRepository;
  private IOrderPaymentRepository $paymentRepository;

  private IPricingProvider $pricingProvider;

  public function setUp(): void {
    parent::setUp();
    $orders = [
      new CustomerOrder(
        id: "customer-order-id",
        items: [new CustomerOrderItem("customer-order-item-id", "product-id", 2_000)],
        customerId: "customer-id", 
        depositId: "deposit-id", 
        authorId: "author-id"
      ),
    ];

    $this->orderRepository = new InMemoryCustomerOrderRepository($orders);
    $this->paymentRepository = new InMemoryOrderPaymentRepository();
    $this->pricingProvider = new FixedPricingProvider();

    $this->commandHandler = new PayCustomerOrderCommandHandler(
      new FixedIdProvider("payment-id"),
      $this->orderRepository,
      $this->paymentRepository,
      $this->pricingProvider,
      new FixedAuthenticatedUserProvider(new AuthenticatedUser('author-id'))
    );
  }

  public function test_happyPath_ShouldPayTotalCustomerOrder() {
    $command = new PayCustomerOrderCommand("customer-order-id", 2_000_000);
    $response = $this->commandHandler->execute($command);
    $payment = $this->paymentRepository->findById($response->getId());

    $customerOrder = $this->orderRepository->findById("customer-order-id");

    $this->assertEquals("payment-id", $response->getId());
    $this->assertNotNull($payment);
    $this->assertEquals(2_000_000, $payment->getAmount());
    $this->assertEquals("customer-order-id", $payment->getCustomerOrderId());
    $this->assertEquals('PAYED', $customerOrder->getPaymentStatus());
    $this->assertTrue($customerOrder->isDeliveryAuthorized());
    $this->assertEquals('author-id', $payment->getAuthorId());
  }

  public function test_happyPath_ShouldPayPartialCustomerOrder() {
    $command = new PayCustomerOrderCommand("customer-order-id", 1_000_000);
    $response = $this->commandHandler->execute($command);
    $payment = $this->paymentRepository->findById($response->getId());

    $customerOrder = $this->orderRepository->findById("customer-order-id");

    $this->assertEquals("payment-id", $response->getId());
    $this->assertNotNull($payment);
    $this->assertEquals(1_000_000, $payment->getAmount());
    $this->assertEquals("customer-order-id", $payment->getCustomerOrderId());
    $this->assertEquals('PARTIALLY_PAYED', $customerOrder->getPaymentStatus());
    $this->assertFalse($customerOrder->isDeliveryAuthorized());
    $this->assertEquals('author-id', $payment->getAuthorId());
  }

  public function test_WhenCustomerOrderNotFound_ShouldThrow() {
    $this->expectExceptionMessage("Customer order not found");
    $this->commandHandler->execute(
      new PayCustomerOrderCommand("not-found-id", 0)
    );
  }
}