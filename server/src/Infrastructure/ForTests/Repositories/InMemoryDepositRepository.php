<?php

namespace App\Infrastructure\ForTests\Repositories;

use App\Application\Ports\Repositories\IDepositRepository;
use App\Domain\Entity\Deposit;

class InMemoryDepositRepository implements IDepositRepository {
  public function __construct(private array $deposits = []) {}

  public function findById(string $id): ?Deposit {
    foreach($this->deposits as $deposit) {
      if ($deposit->getId() === $id) {
        return $deposit;
      }
    }

    return null;
  }
}
