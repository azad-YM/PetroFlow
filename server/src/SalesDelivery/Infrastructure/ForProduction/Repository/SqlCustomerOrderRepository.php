<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Domain\Entity\Customer;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\SalesDelivery\Domain\Entity\CustomerOrderItem;
use App\SalesDelivery\Domain\Entity\Deposit;
use App\SalesDelivery\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class SqlCustomerOrderRepository extends ServiceEntityRepository implements ICustomerOrderRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, CustomerOrder::class);
  }

  public function findById(string $id): ?CustomerOrder {
    return $this->hydrate($this->find($id));
  }

  public function save(CustomerOrder $customerOrder): void {
    $em = $this->getEntityManager();
    $customer = $em->getReference(Customer::class, $customerOrder->getCustomerId());
    $deposit = $em->getReference(Deposit::class, $customerOrder->getDepositId());
    $author = $em->getReference(User::class, $customerOrder->getAuthorId());
    $items = new ArrayCollection($customerOrder->getItems());
    $items->forAll(fn($key, CustomerOrderItem $item) => $item->setOrder($customerOrder));

    $customerOrder
      ->setCustomer($customer)
      ->setDeposit($deposit)
      ->setAuthor($author)
      ->setCollectionItemsForDoctrine($items)
    ;

    $em->persist($customerOrder);
  }

  public function hydrate(?CustomerOrder $customerOrder): ?CustomerOrder {
    if (!$customerOrder) {
      return null;
    }

    return $customerOrder
      ->setCustomerId($customerOrder->getCustomer()->getId())
      ->setDepositId($customerOrder->getDeposit()->getId())
      ->setAuthorId($customerOrder->getAuthor()->getId())
      ->setItems($customerOrder->getCollectionItemsForDoctrine()->toArray())
    ;
  }
}