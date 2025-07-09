<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\IVehicleRepostiory;
use App\SalesDelivery\Domain\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlVehicleRepository extends ServiceEntityRepository implements IVehicleRepostiory {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Vehicle::class);
  }

  public function findById(string $id): ?Vehicle {
    return $this->find($id);
  }

  public function save(Vehicle $vehicle): void {
    $em = $this->getEntityManager();
    $em->persist($vehicle);
  }
}