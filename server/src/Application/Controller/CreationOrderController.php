<?php

namespace App\Application\Controller;

use App\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommand;
use App\Lib\AppController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreationOrderController extends AppController {
  #[Route('/api/create-customer-order', format: "json")]
  public function createOrder(#[MapRequestPayload] CreateCustomerOrderCommand $command) {
    return $this->dispatch($command);
  }
}