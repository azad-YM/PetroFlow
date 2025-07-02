<?php

namespace App\Infrastructure\ForTests\Service;

use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Domain\Model\AuthenticatedUser;

class FixedAuthenticatedUserProvider implements IAuthenticatedUserProvider {
  public function __construct(private AuthenticatedUser $user) {}

  public function getUser(): AuthenticatedUser {
    return $this->user;
  }
}