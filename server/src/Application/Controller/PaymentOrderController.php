<?php

namespace App\Application\Controller;

use App\Application\Commands\PayCustomerOrder\PayCustomerOrderCommand;
use App\Lib\AppController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentOrderController extends AppController {

  #[Route('/api/pay-customer-order', format: "json")]
  public function payOrder(#[MapRequestPayload] PayCustomerOrderCommand $command) {
    return $this->dispatch($command);
  }
}