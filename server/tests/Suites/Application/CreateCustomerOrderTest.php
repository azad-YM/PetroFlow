<?php

namespace App\Tests\Suites\Application;

use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\ICustomerRepository;
use App\Application\Ports\Repositories\IDepositRepository;
use App\Application\Ports\Repositories\IProductRepository;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Deposit;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductStock;
use App\Tests\Infrastructure\ApplicationTestCase;

class CreateCustomerOrderTest extends ApplicationTestCase {
  public function test_happyPath() {
    $client = self::initialize();
    $product = new Product('product-id');

    /**  @var ICustomerRepository $customerRepository */
    $customerRepository = self::getContainer()->get(ICustomerRepository::class);
    $customerRepository->save(new Customer('customer-id'));

    /**  @var IProductRepository $productRepository */
    $productRepository = self::getContainer()->get(IProductRepository::class);
    $productRepository->save($product);

    /**  @var IDepositRepository $depositRepository */
    $depositRepository = self::getContainer()->get(IDepositRepository::class);
    $depositRepository->save(new Deposit(
      'deposit-id', 
      [new ProductStock($product, 300)]
    ));

    $this->request('POST', '/api/create-customer-order', [
      "customerId" => "customer-id",
      "productId" => "product-id",
      "depositId" => "deposit-id",
      "quantity" => 200,
    ]);

    $this->assertResponseStatusCodeSame(200);

    $response = $client->getResponse();
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
  }
}