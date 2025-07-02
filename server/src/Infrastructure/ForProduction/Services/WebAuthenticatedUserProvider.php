<?php

namespace App\Infrastructure\ForProduction\Services;

use App\Application\Ports\Services\IAuthenticatedUserProvider;
use App\Domain\Model\AuthenticatedUser;

class WebAuthenticatedUserProvider implements IAuthenticatedUserProvider {
  public function getUser(): AuthenticatedUser {
    return new AuthenticatedUser("web-user-id");
  }
}