<?php

namespace App\SalesDelivery\Application\Controller;

use App\Lib\AppController;
use App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommand;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RecordingDeliveryMeasurementController extends AppController {

  #[Route('/api/record-delivery-measurement', format: "json")]
  public function record(#[MapRequestPayload] RecordDeliveryMeasurementCommand $command) {
    return $this->dispatch($command);
  }
}