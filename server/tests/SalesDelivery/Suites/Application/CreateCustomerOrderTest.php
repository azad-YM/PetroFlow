<?php

namespace App\Tests\SalesDelivery\Suites\Application;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Domain\Entity\Customer;
use App\SalesDelivery\Domain\Entity\Deposit;
use App\SalesDelivery\Domain\Entity\Product;
use App\SalesDelivery\Domain\Entity\ProductStock;
use App\SalesDelivery\Domain\Entity\User;
use App\Tests\SalesDelivery\Fixtures\CustomerFixture;
use App\Tests\SalesDelivery\Fixtures\DepositFixture;
use App\Tests\SalesDelivery\Fixtures\ProductFixture;
use App\Tests\SalesDelivery\Fixtures\UserFixture;
use App\Tests\Shared\Infrastructure\ApplicationTestCase;

class CreateCustomerOrderTest extends ApplicationTestCase {
  public function setUp(): void {
    $client = self::initialize();

    $userFixture = new UserFixture( User::create(
      id: "user-id", 
      email: "azad@gmail.com", 
      password: "azerty"
    ));

    $customerFixture =  new CustomerFixture(new Customer("customer-id"));

    $product = new Product('product-id');
    $productFixture = new ProductFixture($product);

    $stocks = [new ProductStock($product, 300)];
    $deposit = new Deposit('deposit-id', $stocks);
    $depositFixture = new DepositFixture($deposit);

    $this->load([
      $userFixture,
      $productFixture,
      $depositFixture,
      $customerFixture,
    ]);

    $userFixture->authenticate($client);
  }

  public function test_happyPath() {
    $this->request('POST', '/api/create-customer-order', [
      "customerId" => "customer-id",
      "items" => [["productId" => "product-id", "quantity" => 200]],
      "depositId" => "deposit-id",
    ]);

    $this->assertResponseStatusCodeSame(200);

    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);
    $id = $data["id"];

    /**  @var ICustomerOrderRepository $customerOrderRepository */
    $customerOrderRepository = self::getContainer()->get(ICustomerOrderRepository::class);
    $customerOrder = $customerOrderRepository->findById($id);

    $this->assertNotNull($customerOrder);
    $this->assertEquals("customer-id", $customerOrder->getCustomerId());
    $this->assertEquals("product-id", $customerOrder->getItems()[0]->getProductId());
    $this->assertEquals(200, $customerOrder->getItems()[0]->getQuantity());
    $this->assertEquals("deposit-id", $customerOrder->getDepositId());
    $this->assertEquals("NOT_PAYED", $customerOrder->getPaymentStatus());
    $this->assertEquals("NOT_DELIVERED", $customerOrder->getDeliveryStatus());
    $this->assertEquals($customerOrder->getAuthorId(), "user-id");
  }

  public function test_CustomerNotFound() {
    $this->request('POST', '/api/create-customer-order', [
      "customerId" => "not-found-id",
      "items" => [["productId" => "product-id", "quantity" => 200]],
      "depositId" => "deposit-id",
    ]);

    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertResponseStatusCodeSame(404);
    $this->assertEquals('Customer not found', $data['message']);
  }

  public function test_InvalidInput() {
    $this->request('POST', '/api/create-customer-order', [
      "customerId" => "",
      "depositId" => "",
      "items" => [["productId" => "", "quantity" => 200]],
    ]);

    $this->assertResponseStatusCodeSame(400);
  }
}