<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\SalesDelivery\Domain\Entity\CustomerDelivery;
use App\SalesDelivery\Domain\Entity\Driver;
use App\SalesDelivery\Domain\Entity\User;
use App\SalesDelivery\Domain\Entity\Vehicle;
use App\SalesDelivery\Domain\Model\ManuallyEnteredMeasurement;
use App\SalesDelivery\Domain\Entity\Measurement;
use App\SalesDelivery\Domain\Factory\DeliveryMeasurementFactory;
use App\SalesDelivery\Domain\Model\MeasuredByCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    $items = new ArrayCollection();
    foreach($customerDelivery->getMeasurements() as $item) {
      if ($item instanceof ManuallyEnteredMeasurement) {
        $measurement = new Measurement(
          deliveryId: $customerDelivery->getId(),
          tankId: $item->getTankId(),
          authorId: $item->getAuthorId(),
          quantity: $item->getQuantity()
        );

        $measurement->setDelivery($customerDelivery);
        $items->add($measurement);
      }

      if ($item instanceof MeasuredByCounter) {
        $measurement = new Measurement(
          deliveryId: $customerDelivery->getId(),
          tankId: $item->getTankId(),
          authorId: $item->getAuthorId(),
          start: $item->getStart(),
          end: $item->getEnd()
        );
        $measurement->setDelivery($customerDelivery);
        $items->add($measurement);
      }
    }
    
    $customerDelivery->setCollectionMeasurementsForDoctrine($items);
    $em->persist($customerDelivery);
  }

  public function hydrate(?CustomerDelivery $customerDelivery) {
    if (!$customerDelivery) {
      return null;
    }

    foreach($customerDelivery->getCollectionMeasurementsForDoctrine() as $measurement) {
      $customerDelivery->addMeasurement(DeliveryMeasurementFactory::createFromEntity($measurement));
    }

    return $customerDelivery
      ->setDriverId($customerDelivery->getDriver()->getId())
      ->setVehicleId($customerDelivery->getVehicle()->getId())
      ->setAuthorId($customerDelivery->getAuthor()->getId())
    ;
  }
}