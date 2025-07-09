<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\Deposit;

interface IDepositRepository {
  public function findById(string $id): ?Deposit;
  public function save(Deposit $deposit): void;
}