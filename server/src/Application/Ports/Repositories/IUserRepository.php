<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\User;

interface IUserRepository {
  public function save(User $user): void;
  public function findById(string $userId): ?User;
}