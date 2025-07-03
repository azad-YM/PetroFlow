<?php

namespace App\Application\Controller;

use App\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommand;
use App\Application\Commands\CreateCustomerOrder\CreateCustomerOrderCommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreationOrderController extends AbstractController {
  #[Route('/api/create-customer-order', format: "json")]
  public function createOrder(
    CreateCustomerOrderCommandHandler $commandHandler, 
    EntityManagerInterface $entityManager, 
    #[MapRequestPayload] CreateCustomerOrderCommand $command
  ) {
    $response = $commandHandler->execute($command);
    $entityManager->flush();
    return $this->json($response);
  }
}