<?php

namespace App\Tests\SalesDelivery\Suites\Unit\Commands;

use App\SalesDelivery\Application\Commands\PrepareDelivery\PrepareDeliveryCommand;
use App\SalesDelivery\Application\Commands\PrepareDelivery\PrepareDeliveryCommandHandler;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Application\Ports\Repositories\IDriverRepostiory;
use App\SalesDelivery\Application\Ports\Repositories\IVehicleRepostiory;
use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\Entity\Driver;
use App\SalesDelivery\Domain\Entity\Vehicle;
use App\SalesDelivery\Domain\Model\AuthenticatedUser;
use App\SalesDelivery\Domain\Model\PaymentPlan;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryCustomerDeliveryRepository;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryCustomerOrderRepository;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryDriverRepository;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryVehicleRepository;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedAuthenticatedUserProvider;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedIdProvider;
use PHPUnit\Framework\TestCase;

class PrepareDeliveryTest extends TestCase {
  private IAuthenticatedUserProvider $userProvider;
  private PrepareDeliveryCommandHandler $commandHandler;

  private ICustomerOrderRepository $orderRepository;
  private IDriverRepostiory $driverRepository;
  private IVehicleRepostiory $vehicleRepository;
  private ICustomerDeliveryRepository $customerDeliveryRepository;

  public function setUp(): void {
    parent::setUp();
    $unAuthorizedOrder = new CustomerOrder(
      id: "unauthorized-delivery-order-id",
      items: [new CustomerOrderItem("customer-order-item-id", "product-id", 2_000)],
      customerId: "customer-id", 
      depositId: "deposit-id", 
      authorId: "author-id"
    );
    $unAuthorizedOrder->registerPayment(100, new PaymentPlan(200));

    $authorizedOrder = new CustomerOrder(
      id: "customer-order-id",
      items: [new CustomerOrderItem("customer-order-item-id", "product-id", 2_000)],
      customerId: "customer-id", 
      depositId: "deposit-id", 
      authorId: "author-id"
    );
    $authorizedOrder->registerPayment(200, new PaymentPlan(200));

    $orders = [
      $unAuthorizedOrder,
      $authorizedOrder
    ];

    $this->userProvider = new FixedAuthenticatedUserProvider(new AuthenticatedUser("author-id"));
    $this->orderRepository = new InMemoryCustomerOrderRepository($orders);
    $this->driverRepository = new InMemoryDriverRepository([new Driver('driver-id')]);
    $this->vehicleRepository = new InMemoryVehicleRepository([new Vehicle('vehicle-id')]);
    $this->customerDeliveryRepository = new InMemoryCustomerDeliveryRepository();

    $this->commandHandler = new PrepareDeliveryCommandHandler(
      new FixedIdProvider('delivery-id'),
      $this->orderRepository,
      $this->driverRepository,
      $this->vehicleRepository,
      $this->userProvider,
      $this->customerDeliveryRepository
    );
  }

  public function test_happyPath_ShouldPrepareDelivery() {
    $command = new PrepareDeliveryCommand(
      customerOrderId: "customer-order-id", 
      driverId: "driver-id", 
      vehicleId: "vehicle-id", 
      deliveryAt: "12/07/2025"
    );

    $response = $this->commandHandler->execute($command);
    $delivery = $this->customerDeliveryRepository->findById($response->getId());
    $order = $this->orderRepository->findById('customer-order-id');

    $this->assertNotNull($response);
    $this->assertEquals("delivery-id", $delivery->getId());
    $this->assertEquals("customer-order-id", $delivery->getOrderId());
    $this->assertEquals("driver-id", $delivery->getDriverId());
    $this->assertEquals("vehicle-id", $delivery->getVehicleId());
    $this->assertEquals('12/07/2025', $delivery->getDeliveryAt());
    $this->assertEquals("author-id", $delivery->getAuthorId());
    $this->assertEquals('INPROGRESS', $order->getDeliveryStatus());
  }

  public function test_WhenCustomerOrderNotFound_ShouldThrow() {
    $command = new PrepareDeliveryCommand(
      customerOrderId: "not-found", 
      driverId: "driver-id", 
      vehicleId: "vehicle-id",
      deliveryAt: "delivery-date"
    );

    $this->expectExceptionMessage('Customer order not found');
    $this->commandHandler->execute($command);
  }

  public function test_WhenCommandIsUnauthorizedForDelivery_ShouldThrow() {
    $command = new PrepareDeliveryCommand(
      customerOrderId: "unauthorized-delivery-order-id", 
      driverId: "driver-id", 
      vehicleId: "vehicle-id",
      deliveryAt: 'delivery-date'
    );

    $this->expectExceptionMessage('Order unauthorized for delivery');
    $this->commandHandler->execute($command);
  }

  public function test_WhenDriverNotFound_ShouldThrow() {
    $command = new PrepareDeliveryCommand(
      customerOrderId: "customer-order-id", 
      driverId: "driver-not-found", 
      vehicleId: "vehicle-id",
      deliveryAt: 'delivery-date'
    );

    $this->expectExceptionMessage('Driver not found');
    $this->commandHandler->execute($command);
  }

  public function test_WhenVehicleNotFound_ShouldThrow() {
    $command = new PrepareDeliveryCommand(
      customerOrderId: "customer-order-id", 
      driverId: "driver-id", 
      vehicleId: "not-found-vehicle",
      deliveryAt: "delivery-date"
    );

    $this->expectExceptionMessage('Vehicle not found');
    $this->commandHandler->execute($command);
  }
}