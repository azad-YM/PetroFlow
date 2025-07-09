<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\IDriverRepostiory;
use App\SalesDelivery\Domain\Entity\Driver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlDriverRepository extends ServiceEntityRepository implements IDriverRepostiory {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Driver::class);
  }

  public function findById(string $id): ?Driver {
    return $this->find($id);
  }

  public function save(Driver $driver): void {
    $em = $this->getEntityManager();
    $em->persist($driver);
  }
}