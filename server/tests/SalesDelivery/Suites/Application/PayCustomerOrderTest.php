<?php

namespace App\Tests\SalesDelivery\Suites\Application;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Application\Ports\Repositories\IOrderPaymentRepository;
use App\SalesDelivery\Domain\Entity\Customer;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\Entity\Deposit;
use App\SalesDelivery\Domain\Entity\Product;
use App\SalesDelivery\Domain\Entity\ProductStock;
use App\SalesDelivery\Domain\Entity\User;
use App\Tests\SalesDelivery\Fixtures\CustomerFixture;
use App\Tests\SalesDelivery\Fixtures\CustomerOrderFixture;
use App\Tests\SalesDelivery\Fixtures\DepositFixture;
use App\Tests\SalesDelivery\Fixtures\ProductFixture;
use App\Tests\SalesDelivery\Fixtures\UserFixture;
use App\Tests\Shared\Infrastructure\ApplicationTestCase;

class PayCustomerOrderTest extends ApplicationTestCase {
  public function setUp(): void {
    parent::setUp();
    $client = self::initialize();

    $userFixture =  new UserFixture(
      User::create("user-id", "azad@gmail.com", "azerty")
    );

    $customerFixture =  new CustomerFixture(new Customer("customer-id"));

    $product = new Product('product-id');
    $productFixture = new ProductFixture($product);

    $stocks = [new ProductStock($product, 3_000)];
    $deposit = new Deposit('deposit-id', $stocks);
    $depositFixture = new DepositFixture($deposit);

    $order = new CustomerOrder(
      id: "customer-order-id", 
      items: [new CustomerOrderItem("costomer-order-item-id", "product-id", 2_000)], 
      customerId: "customer-id", 
      depositId: "deposit-id",
      authorId: "user-id"
    );
    $orderFixture = new CustomerOrderFixture($order);

    $this->load([
      $userFixture, 
      $customerFixture,
      $productFixture,
      $depositFixture,
      $orderFixture, 
    ]);

    $userFixture->authenticate($client);
  }
  public function test_happyPath() {
    $this->request('POST', '/api/pay-customer-order', [
      "customerOrderId" => "customer-order-id",
      "amount" => 2_000_000
    ]);

    $this->assertResponseStatusCodeSame(200);

    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);
    $paymentId = $data['id'];

    /**  @var IOrderPaymentRepository $customerOrderRepository */
    $orderPaymentRepository = self::getContainer()->get(IOrderPaymentRepository::class);
    $payment = $orderPaymentRepository->findById($paymentId);
    $order = self::getContainer()->get(ICustomerOrderRepository::class)->findById("customer-order-id");

    $this->assertNotNull($payment);
    $this->assertEquals(2_000_000, $payment->getAmount());
    $this->assertEquals("customer-order-id", $payment->getCustomerOrderId());
    $this->assertEquals("user-id", $payment->getAuthorId());
    $this->assertEquals('PAYED', $order->getPaymentStatus());
  }

  public function test_CustomerOrderNotFound() {
    $this->request('POST', '/api/pay-customer-order', [
      "customerOrderId" => "not-found-id",
      "amount" => 2_000_000
    ]);

    $this->assertResponseStatusCodeSame(404);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertEquals('Customer order not found', $data['message']);
  }

  public function test_InvalidInput() {
    $this->request('POST', '/api/pay-customer-order', [
      "customerOrderId" => "",
      "amount" => -2_000_0000
    ]);

    $this->assertResponseStatusCodeSame(400);
  }
}