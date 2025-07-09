<?php

namespace App\SalesDelivery\Application\Controller;

use App\Lib\AppController;
use App\SalesDelivery\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommand;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreationOrderController extends AppController {
  #[Route('/api/create-customer-order', format: "json")]
  public function createOrder(#[MapRequestPayload] CreateCustomerOrderCommand $command) {
    return $this->dispatch($command);
  }
}