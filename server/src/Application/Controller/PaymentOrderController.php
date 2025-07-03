<?php

namespace App\Application\Controller;

use App\Application\Commands\PayCustomerOrder\PayCustomerOrderCommand;
use App\Application\Commands\PayCustomerOrder\PayCustomerOrderCommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentOrderController extends AbstractController {

  #[Route('/api/pay-customer-order', format: "json")]
  public function payOrder(
    PayCustomerOrderCommandHandler $comandHandler,
    #[MapRequestPayload] PayCustomerOrderCommand $command,
    EntityManagerInterface $entityManager
  ) {

    $response = $comandHandler->execute($command);
    $entityManager->flush();
    return $this->json($response);
  }
}