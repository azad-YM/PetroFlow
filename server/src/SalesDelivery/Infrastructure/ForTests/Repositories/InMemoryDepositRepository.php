<?php

namespace App\SalesDelivery\Infrastructure\ForTests\Repositories;

use App\SalesDelivery\Application\Ports\Repositories\IDepositRepository;
use App\SalesDelivery\Domain\Entity\Deposit;

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

  public function save(Deposit $deposit): void {
    array_push($this->deposits, $deposit);
  }
}
