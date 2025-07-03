<?php

namespace App\Tests\Suites\Application;

use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Deposit;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductStock;
use App\Domain\Entity\User;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\DepositFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Infrastructure\ApplicationTestCase;

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
      "productId" => "product-id",
      "depositId" => "deposit-id",
      "quantity" => 200,
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
    $this->assertEquals("product-id", $customerOrder->getProductId());
    $this->assertEquals("deposit-id", $customerOrder->getDepositId());
    $this->assertEquals(200, $customerOrder->getQuantity());
    $this->assertEquals("EN_ATTENTE_PAIEMENT", $customerOrder->getStatus());
    $this->assertEquals($customerOrder->getAuthorId(), "user-id");
  }

  public function test_CustomerNotFound() {
    $this->request('POST', '/api/create-customer-order', [
      "customerId" => "not-found-id",
      "depositId" => "deposit-id",
      "productId" => "product-id",
      "quantity" => 200,
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
      "productId" => "",
      "quantity" => 200,
    ]);

    $this->assertResponseStatusCodeSame(400);
  }
}