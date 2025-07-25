<?php

namespace App\Tests\SalesDelivery\Suites\Application;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Domain\Entity\Customer;
use App\SalesDelivery\Domain\Entity\CustomerDelivery;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\Deposit;
use App\SalesDelivery\Domain\Entity\Driver;
use App\SalesDelivery\Domain\Entity\Product;
use App\SalesDelivery\Domain\Entity\ProductStock;
use App\SalesDelivery\Domain\Entity\User;
use App\SalesDelivery\Domain\Entity\Vehicle;
use App\SalesDelivery\Domain\Model\ManuallyEnteredMeasurement;
use App\SalesDelivery\Domain\Model\MeasuredByCounter;
use App\Tests\SalesDelivery\Fixtures\CustomerFixture;
use App\Tests\SalesDelivery\Fixtures\CustomerOrderFixture;
use App\Tests\SalesDelivery\Fixtures\DeliveryFixture;
use App\Tests\SalesDelivery\Fixtures\DepositFixture;
use App\Tests\SalesDelivery\Fixtures\DriverFixture;
use App\Tests\SalesDelivery\Fixtures\ProductFixture;
use App\Tests\SalesDelivery\Fixtures\UserFixture;
use App\Tests\SalesDelivery\Fixtures\VehicleFixture;
use App\Tests\Shared\Infrastructure\ApplicationTestCase;

class RecordDeliveryMeasurementTest extends ApplicationTestCase {
  public function setUp(): void {
    $this->initialize();
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

    $userFixture = new UserFixture(
      User::create("user-id", "azad@gmail.com", "azerty")
    );
    $deliveryFixture = new DeliveryFixture(new CustomerDelivery(
      "delivery-id",
      "customer-order-id",
      "driver-id",
      "vehicle-id",
      "12/07/2025",
      "user-id"
    ));

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
      $deliveryFixture,
    ]);

    $userFixture->authenticate(self::$client);
  }

  public function test_happyPath() {
    $this->request(
      'POST',
      '/api/record-delivery-measurement',
      [
        "deliveryId" => "delivery-id", 
        "measurements" => [
          ["start" => 12_000, "end" => 10_000, "tankId" => 'tank-id'], 
          ["tankId" => 'tank-2', "quantity" => 1_000]
        ]
      ]
    );

    $this->assertResponseStatusCodeSame(200);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $deliveryId = $data['id'];
    $delivery = self::getContainer()->get(ICustomerDeliveryRepository::class)->findById($deliveryId);

    $this->assertNotNull($delivery);
    $this->assertCount(2, $delivery->getMeasurements());
    $this->assertEquals(3_000, $delivery->getDelivredQuantity());

    $this->assertInstanceOf(MeasuredByCounter::class, $delivery->getMeasurements()[0]);
    $this->assertEquals("tank-id", $delivery->getMeasurements()[0]->getTankId());

    $this->assertInstanceOf(ManuallyEnteredMeasurement::class, $delivery->getMeasurements()[1]);
    $this->assertEquals("tank-2", $delivery->getMeasurements()[1]->getTankId());
  }

  public function test_WhenMeasurementIsEmpty_ShouldInvalidInput() {
    $this->request(
      'POST',
      '/api/record-delivery-measurement',
      [
        "deliveryId" => "delivery-id", 
        "measurements" => []
      ]
    );

    $this->assertResponseStatusCodeSame(400);
  }

  public function test_WhenEncCounterNotDefined_ShouldInvalidInput() {
    $this->request(
      'POST',
      '/api/record-delivery-measurement',
      [
        "deliveryId" => "delivery-id", 
        "measurements" => [
          ["start" => 10_000, "tankId" => 'tank-id'], 
        ]
      ]
    );

    $this->assertResponseStatusCodeSame(400);
  }

  public function test_WhenStartCounterIsLessThanEnd_ShouldInvalidInput() {
    $this->request(
      'POST',
      '/api/record-delivery-measurement',
      [
        "deliveryId" => "delivery-id", 
        "measurements" => [
          ["start" => 10_000, "end" => 12_000, "tankId" => 'tank-id'], 
        ]
      ]
    );

    $this->assertResponseStatusCodeSame(400);
  }

  public function test_CustomerDeliveryNotFound() {
    $this->request(
      'POST',
      '/api/record-delivery-measurement',
      [
        "deliveryId" => "not-found-id", 
        "measurements" => [["tankId" => 'tank-2', "quantity" => 1_000]]
      ]
    );

    $this->assertResponseStatusCodeSame(404);
    $response = self::$client->getResponse();
    $data = json_decode($response->getContent(), true);

    $this->assertEquals("Delivery not found", $data["message"]);
  }
}