<?php

namespace App\Tests\Suites\Application;

use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Domain\Entity\Customer;
use App\Domain\Entity\CustomerOrder;
use App\Domain\Entity\Deposit;
use App\Domain\Entity\Driver;
use App\Domain\Entity\Product;
use App\Domain\Entity\ProductStock;
use App\Domain\Entity\User;
use App\Domain\Entity\Vehicle;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\CustomerOrderFixture;
use App\Tests\Fixtures\DepositFixture;
use App\Tests\Fixtures\DriverFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\UserFixture;
use App\Tests\Fixtures\VehicleFixture;
use App\Tests\Infrastructure\ApplicationTestCase;

class PrepareDeliveryTest extends ApplicationTestCase {
  public function setUp(): void {
    $this->initialize();
    $userFixture = new UserFixture(
      User::create("user-id", "azad@gmail.com", "azerty")
    );
    $customerFixture =  new CustomerFixture(new Customer("customer-id"));

    $product = new Product('product-id');
    $productFixture = new ProductFixture($product);

    $stocks = [new ProductStock($product, 3_000)];
    $deposit = new Deposit('deposit-id', $stocks);
    $depositFixture = new DepositFixture($deposit);

    $driverFixture = new DriverFixture(new Driver('driver-id'));
    $vehicleFixture = new VehicleFixture(new Vehicle('vehicle-id'));
    $order = new CustomerOrder(
      id: "customer-order-id", 
      items: [], 
      customerId: "customer-id", 
      depositId: "deposit-id",
      authorId: "user-id"
    );

    $order->authorizeDelivery();
    $orderFixture = new CustomerOrderFixture($order);

    $this->load([
      $userFixture,
      $customerFixture,
      $productFixture,
      $depositFixture,
      $driverFixture,
      $vehicleFixture,
      $orderFixture,
    ]);

    $userFixture->authenticate(self::$client);
  }

  public function test_happyPath() {
    $this->request('POST', '/api/prepare-delivery', [
      "customerOrderId" => "customer-order-id", 
      "driverId" => "driver-id", 
      "vehicleId" => "vehicle-id", 
      "deliveryAt" => "12/07/2025",
    ]);

    $this->assertResponseStatusCodeSame(200);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $deliveryId = $data["id"];
    $delivery = self::getContainer()->get(ICustomerDeliveryRepository::class)->findById($deliveryId);

    $this->assertNotNull($delivery);
    $this->assertEquals("12/07/2025", $delivery->getDeliveryAt());
    $this->assertEquals("driver-id", $delivery->getDriverId());
    $this->assertEquals("vehicle-id", $delivery->getVehicleId());
    $this->assertEquals("user-id", $delivery->getAuthorId());
  }

  public function test_InvalidInput() {
    $this->request('POST', '/api/prepare-delivery', [
      "customerOrderId" => "", 
      "driverId" => "", 
      "vehicleId" => "", 
      "deliveryAt" => "12/07/2025",
    ]);

    $this->assertResponseStatusCodeSame(400);
  }

  public function test_CustomerOrderNotFound() {
    $this->request('POST', '/api/prepare-delivery', [
      "customerOrderId" => "not-found-id",
      "driverId" => "driver-id", 
      "vehicleId" => "vehicle-id", 
      "deliveryAt" => "12/07/2025",
    ]);

    $this->assertResponseStatusCodeSame(404);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertEquals('Customer order not found', $data['message']);
  }

  public function test_DriverNotFound() {
    $this->request('POST', '/api/prepare-delivery', [
      "customerOrderId" => "customer-order-id",
      "driverId" => "not-found", 
      "vehicleId" => "vehicle-id", 
      "deliveryAt" => "12/07/2025",
    ]);

    $this->assertResponseStatusCodeSame(404);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertEquals('Driver not found', $data['message']);
  }

  public function test_VehicleNotFound() {
    $this->request('POST', '/api/prepare-delivery', [
      "customerOrderId" => "customer-order-id",
      "driverId" => "driver-id", 
      "vehicleId" => "not-found", 
      "deliveryAt" => "12/07/2025",
    ]);

    $this->assertResponseStatusCodeSame(404);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertEquals('Vehicle not found', $data['message']);
  }
}