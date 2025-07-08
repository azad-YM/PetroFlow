<?php

namespace App\Infrastructure\ForProduction\Repository;

use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Domain\Entity\CustomerDelivery;
use App\Domain\Entity\Driver;
use App\Domain\Entity\User;
use App\Domain\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlCustomerDeliveryRepository extends ServiceEntityRepository implements ICustomerDeliveryRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, CustomerDelivery::class);
  }

  public function findById(string $id): ?CustomerDelivery {
    return $this->hydrate($this->find($id));
  }

  public function save(CustomerDelivery $customerDelivery): void {
    $em = $this->getEntityManager();
    $driver = $em->getReference(Driver::class, $customerDelivery->getDriverId());
    $vehicle = $em->getReference(Vehicle::class, $customerDelivery->getVehicleId());
    $author = $em->getReference(User::class, $customerDelivery->getAuthorId());

    $customerDelivery->setDriver($driver);
    $customerDelivery->setVehicle($vehicle);
    $customerDelivery->setAuthor($author);

    $em->persist($customerDelivery);
  }

  public function hydrate(?CustomerDelivery $customerDelivery) {
    if (!$customerDelivery) {
      return null;
    }

    return $customerDelivery
      ->setDriverId($customerDelivery->getDriver()->getId())
      ->setVehicleId($customerDelivery->getVehicle()->getId())
      ->setAuthorId($customerDelivery->getAuthor()->getId())
    ;
  }
}