<?php 

namespace App\Application\Ports\Services;

use App\Domain\Model\AuthenticatedUser;

interface IAuthenticatedUserProvider {
  public function getUser(): AuthenticatedUser;
}