<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Services;

use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Domain\Model\AuthenticatedUser;

class FixedAuthenticatedUserProvider implements IAuthenticatedUserProvider {
  public function __construct(private AuthenticatedUser $user) {}

  public function getUser(): AuthenticatedUser {
    return $this->user;
  }
}