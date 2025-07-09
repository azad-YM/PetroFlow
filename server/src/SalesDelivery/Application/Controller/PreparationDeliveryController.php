<?php

namespace App\SalesDelivery\Application\Controller;

use App\Lib\AppController;
use App\SalesDelivery\Application\Commands\PrepareDelivery\PrepareDeliveryCommand;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PreparationDeliveryController extends AppController {
  #[Route("/api/prepare-delivery", format: "json")]
  public function prepare(#[MapRequestPayload] PrepareDeliveryCommand $command) {
    return $this->dispatch($command);
  }
}