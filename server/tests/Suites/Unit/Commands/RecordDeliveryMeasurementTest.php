<?php

namespace App\Tests\Suites\Unit\Commands;

use App\Application\Commands\RecordDeliveryMeasurement\MeasurementDTO;
use App\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommand;
use App\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommandHandler;
use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Domain\Entity\CustomerDelivery;
use App\Infrastructure\ForTests\Repositories\InMemoryCustomerDeliveryRepository;
use PHPUnit\Framework\TestCase;

class RecordDeliveryMeasurementTest extends TestCase {
  private RecordDeliveryMeasurementCommandHandler $commandHandler;
  private ICustomerDeliveryRepository $deliveryRepository;

  public function setUp(): void {
    $delivery = new CustomerDelivery(
      "delivery-id", 
      "order-id", 
      "driver-id", 
      "vehicle-id",
      "12/07/2025",
      "author-id"
    );

    $this->deliveryRepository = new InMemoryCustomerDeliveryRepository([$delivery]);
    $this->commandHandler = new RecordDeliveryMeasurementCommandHandler(
      $this->deliveryRepository
    );
  }

  public function test_happyPath_ShouldRecordDeliveryMesuredByCounter() {
    $command = new RecordDeliveryMeasurementCommand(
      "delivery-id",
      [new MeasurementDTO(12_000, 10_000)]
    );

    $response = $this->commandHandler->execute($command);
    $delivery = $this->deliveryRepository->findById("delivery-id");

    $this->assertNotNull($response);
    $this->assertEquals("delivery-id", $delivery->getId());
    $this->assertEquals(2_000, $delivery->getDelivredQuantity());
  }

  public function test_happyPath_ShouldRecordDeliveryMesuredManually() {
    $command = new RecordDeliveryMeasurementCommand(
      "delivery-id",
      [new MeasurementDTO(quantity: 1_500)]
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
      [new MeasurementDTO(12_000, 10_000), new MeasurementDTO(quantity: 1_000)]
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