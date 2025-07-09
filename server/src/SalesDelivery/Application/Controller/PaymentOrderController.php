<?php

namespace App\SalesDelivery\Application\Controller;

use App\Lib\AppController;
use App\SalesDelivery\Application\Commands\PayCustomerOrder\PayCustomerOrderCommand;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentOrderController extends AppController {

  #[Route('/api/pay-customer-order', format: "json")]
  public function payOrder(#[MapRequestPayload] PayCustomerOrderCommand $command) {
    return $this->dispatch($command);
  }
}