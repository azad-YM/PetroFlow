<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\User;

interface IUserRepository {
  public function save(User $user): void;
  public function findById(string $userId): ?User;
}