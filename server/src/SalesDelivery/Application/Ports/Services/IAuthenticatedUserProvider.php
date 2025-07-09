<?php 

namespace App\SalesDelivery\Application\Ports\Services;

use App\SalesDelivery\Domain\Model\AuthenticatedUser;

interface IAuthenticatedUserProvider {
  public function getUser(): AuthenticatedUser;
}