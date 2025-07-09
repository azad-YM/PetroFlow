<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\IDepositRepository;
use App\SalesDelivery\Domain\Entity\Deposit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlDepositRepository extends ServiceEntityRepository implements IDepositRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Deposit::class);
  }

  public function findById(string $id): ?Deposit {
    return $this->find($id);
  }

  public function save(Deposit $deposit): void {
    $em = $this->getEntityManager();
    $em->persist($deposit);
  }
}