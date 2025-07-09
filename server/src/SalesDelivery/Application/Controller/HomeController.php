<?php

namespace App\SalesDelivery\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {
  #[Route('/')]
  public function index() {
    return $this->json(['message' => 'Hello, World!']);
  }
}