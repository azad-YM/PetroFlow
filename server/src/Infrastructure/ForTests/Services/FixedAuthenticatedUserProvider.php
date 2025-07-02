<?php

namespace App\Infrastructure\ForTests\Services;

use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Domain\Model\AuthenticatedUser;

class FixedAuthenticatedUserProvider implements IAuthenticatedUserProvider {
  public function __construct(private AuthenticatedUser $user) {}

  public function getUser(): AuthenticatedUser {
    return $this->user;
  }
}