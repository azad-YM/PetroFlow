<?php

namespace App\Tests\SalesDelivery\Suites\Unit\Commands;

use App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement\MeasurementDTO;
use App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommand;
use App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommandHandler;
use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Domain\Entity\CustomerDelivery;
use App\SalesDelivery\Domain\Model\AuthenticatedUser;
use App\SalesDelivery\Infrastructure\ForTests\Repositories\InMemoryCustomerDeliveryRepository;
use App\SalesDelivery\Infrastructure\ForTests\Services\FixedAuthenticatedUserProvider;
use PHPUnit\Framework\TestCase;

class RecordDeliveryMeasurementTest extends TestCase {
  private RecordDeliveryMeasurementCommandHandler $commandHandler;
  private ICustomerDeliveryRepository $deliveryRepository;

  public function setUp(): void {
    $delivery = new CustomerDelivery(
      "delivery-id", 
      "customer-order-id", 
      "driver-id", 
      "vehicle-id",
      "12/07/2025",
      "user-id"
    );

    $this->deliveryRepository = new InMemoryCustomerDeliveryRepository([$delivery]);
    $this->commandHandler = new RecordDeliveryMeasurementCommandHandler(
      $this->deliveryRepository,
      new FixedAuthenticatedUserProvider(new AuthenticatedUser('author-id'))
    );
  }

  public function test_happyPath_ShouldRecordDeliveryMesuredByCounter() {
    $command = new RecordDeliveryMeasurementCommand(
      "delivery-id",
      [new MeasurementDTO('tank-id', 12_000, 10_000)]
    );

    $response = $this->commandHandler->execute($command);
    $delivery = $this->deliveryRepository->findById("delivery-id");
    $measurement = $delivery->getMeasurements()[0];

    $this->assertNotNull($response);
    $this->assertEquals("delivery-id", $delivery->getId());
    $this->assertEquals(2_000, $delivery->getDelivredQuantity());
    $this->assertEquals("tank-id", $measurement->getTankId());
    $this->assertEquals("author-id", $measurement->getAuthorId());
  }

  public function test_happyPath_ShouldRecordDeliveryMesuredManually() {
    $command = new RecordDeliveryMeasurementCommand(
      "delivery-id",
      [new MeasurementDTO(quantity: 1_500, tankId: 'tank-id')]
    );

    $response = $this->commandHandler->execute($command);
    $delivery = $this->deliveryRepository->findById("delivery-id");

    $this->assertNotNull($response);
    $this->assertEquals("delivery-id", $delivery->getId());
    $this->assertEquals(1_500, $delivery->getDelivredQuantity());
  }

  public function test_happyPath_ShouldRecordDeliveryWithTwoMeasurements() {
    $command = new RecordDeliveryMeasurementCommand(
      "delivery-id",
      [
        new MeasurementDTO(start: 12_000, end: 10_000, tankId: 'tank-id'), 
        new MeasurementDTO(tankId: 'tank-2', quantity: 1_000)
      ]
    );

    $response = $this->commandHandler->execute($command);
    $delivery = $this->deliveryRepository->findById("delivery-id");

    $this->assertNotNull($response);
    $this->assertEquals("delivery-id", $delivery->getId());
    $this->assertEquals(3_000, $delivery->getDelivredQuantity());
  }

  public function test_WhenDeliveryNotFound_ShouldThrow() {
    $command = new RecordDeliveryMeasurementCommand(
      "not found",
      []
    );

    $this->expectExceptionMessage("Delivery not found");
    $this->commandHandler->execute($command);
  }
}