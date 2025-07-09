<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\IOrderPaymentRepository;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\OrderPayment;
use App\SalesDelivery\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlOrderPaymentRepository extends ServiceEntityRepository implements IOrderPaymentRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, OrderPayment::class);
  }

  public function findById(string $id): ?OrderPayment {
    return $this->hydrate($this->find($id));
  }

  public function save(OrderPayment $orderPayment): void {
    $em = $this->getEntityManager();
    $customerOrder = $em->getReference(CustomerOrder::class, $orderPayment->getCustomerOrderId());
    $author = $em->getReference(User::class, $orderPayment->getAuthorId());

    $orderPayment->setCustomerOrder($customerOrder);
    $orderPayment->setAuthor($author);

    $em->persist($orderPayment);
  }

  public function hydrate(?OrderPayment $orderPayment) {
    if (!$orderPayment) {
      return null;
    }

    $orderPayment->setCustomerOrderId($orderPayment->getCustomerOrder()->getId());
    $orderPayment->setAuthorId($orderPayment->getAuthor()->getId());

    return $orderPayment;
  }
}