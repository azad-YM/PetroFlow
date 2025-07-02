<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\Deposit;

interface IDepositRepository {
  public function findById(string $id): ?Deposit;
}