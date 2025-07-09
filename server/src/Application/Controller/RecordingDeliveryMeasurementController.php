<?php

namespace App\Application\Controller;

use App\Application\Commands\RecordDeliveryMeasurement\RecordDeliveryMeasurementCommand;
use App\Lib\AppController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RecordingDeliveryMeasurementController extends AppController {

  #[Route('/api/record-delivery-measurement', format: "json")]
  public function record(#[MapRequestPayload] RecordDeliveryMeasurementCommand $command) {
    return $this->dispatch($command);
  }
}