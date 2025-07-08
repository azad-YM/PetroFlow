<?php

namespace App\Application\Controller;

use App\Application\Commands\PrepareDelivery\PrepareDeliveryCommand;
use App\Lib\AppController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PreparationDeliveryController extends AppController {
  #[Route("/api/prepare-delivery", format: "json")]
  public function prepare(#[MapRequestPayload] PrepareDeliveryCommand $command) {
    return $this->dispatch($command);
  }
}