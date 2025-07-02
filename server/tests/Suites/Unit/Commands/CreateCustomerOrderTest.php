<?php

namespace App\Tests\Suites\Unit\Commands;

use App\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommand;
use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Application\Ports\Repositories\ICustomerRepository;
use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Application\Ports\Services\IIdProvider;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Product;
use App\Domain\Model\AuthenticatedUser;
use App\Infrastructure\ForTests\Repositories\InMemoryCustomerOrderRepository;
use App\Infrastructure\ForTests\Services\FixedAuthenticatedUserProvider;
use PHPUnit\Framework\TestCase;
use App\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommandHandler;
use App\Application\Ports\Repositories\IDepositRepository;
use App\Domain\Entity\Deposit;
use App\Domain\Entity\ProductStock;
use App\Infrastructure\ForTests\Repositories\InMemoryCustomerRepository;
use App\Infrastructure\ForTests\Repositories\InMemoryDepositRepository;
use App\Infrastructure\ForTests\Services\FixedIdProvider;

class CreateCustomerOrderTest extends TestCase {
  private IAuthenticatedUserProvider $userProvider;
  private IIdProvider $idProvider;

  private CreateCustomerOrderCommandHandler $commandHandler;

  private ICustomerOrderRepository $customerOrderRepository;
  private ICustomerRepository $customerRepository;
  private IDepositRepository $depositRepository;

  protected function setUp(): void {
    parent::setUp();
    $product = new Product('product-id');
    $deposits = [
      new Deposit('deposit-id', [new ProductStock($product, 10_000)])
    ];
    $customers = [new Customer('customer-id')];
    $this->userProvider = new FixedAuthenticatedUserProvider(new AuthenticatedUser("user-id"));

    $this->idProvider = new FixedIdProvider('customer-order-id');
    $this->customerOrderRepository = new InMemoryCustomerOrderRepository();
    $this->customerRepository = new InMemoryCustomerRepository($customers);
    $this->depositRepository = new InMemoryDepositRepository($deposits);

    $this->commandHandler = new CreateCustomerOrderCommandHandler(
      $this->idProvider, 
      $this->customerOrderRepository,
      $this->customerRepository,
      $this->depositRepository,
      $this->userProvider
    );
  }

  public function test_happyPath_ShouldCreateCustomerOrder() {
    $response = $this->commandHandler->execute(new CreateCustomerOrderCommand(
      customerId: "customer-id",
      productId: "product-id",
      depositId: "deposit-id",
      quantity: 2_000,
    ));

    $order = $this->customerOrderRepository->findById($response->getId());
    $deposit = $this->depositRepository->findById("deposit-id");

    $this->assertEquals($response->getId(), $order->getId());
    $this->assertNotNull($order);
    $this->assertEquals("customer-id", $order->getCustomerId());
    $this->assertEquals("product-id", $order->getProductId());
    $this->assertEquals("deposit-id", $order->getDepositId());
    $this->assertEquals(2_000, $order->getQuantity());
    $this->assertEquals(2_000, $deposit->getAllocatedStockFor("product-id"));
    $this->assertEquals(10_000 - 2_000, $deposit->getStockForProduct("product-id")->getQuantity());
    $this->assertEquals("EN_ATTENTE_PAIEMENT", $order->getStatus());
    $this->assertEquals("user-id", $order->getAuthorId());
  }

  public function test_WhenCustomerNotDefined_ShouldThrow() {
    $this->expectExceptionMessage("Customer not found");

    $this->commandHandler->execute(new CreateCustomerOrderCommand(
      customerId: "not-found-id",
      productId: "product-id",
      depositId: "deposit-id",
      quantity: 200_000,
    ));
  }

  public function test_WhenProductNotDefined_ShouldThrow() {
    $this->expectExceptionMessage("Product not found");

    $this->commandHandler->execute(new CreateCustomerOrderCommand(
      customerId: "customer-id",
      productId: "not-found-id",
      depositId: "deposit-id",
      quantity: 200_000,
    ));
  }

  public function test_WhenDepositNotDefined_ShouldThrow() {
    $this->expectExceptionMessage("Deposit not found");

    $this->commandHandler->execute(new CreateCustomerOrderCommand(
      customerId: "customer-id",
      productId: "product-id",
      depositId: "not-found-id",
      quantity: 200_000,
    ));
  }

  public function test_WhenStockNotAvailable_ShouldThrow() {
    $this->expectExceptionMessage("Stock not available");

    $this->commandHandler->execute(new CreateCustomerOrderCommand(
      customerId: "customer-id",
      productId: "product-id",
      depositId: "deposit-id",
      quantity: 11_000,
    ));
  }
}