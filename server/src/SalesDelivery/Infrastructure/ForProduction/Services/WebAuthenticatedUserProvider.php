<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Services;

use App\SalesDelivery\Application\Ports\Services\IAuthenticatedUserProvider;
use App\SalesDelivery\Domain\Entity\User;
use App\SalesDelivery\Domain\Model\AuthenticatedUser;
use Symfony\Bundle\SecurityBundle\Security;

class WebAuthenticatedUserProvider implements IAuthenticatedUserProvider {
  public function __construct(private Security $security) {}

  public function getUser(): AuthenticatedUser {
    $user = $this->security->getUser();
    if (!($user instanceof User)) {
      throw new \Exception("User not found");
    }

    return new AuthenticatedUser($user->getId());
  }
}