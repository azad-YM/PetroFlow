<?php

namespace App\Infrastructure\ForProduction\Repository;

use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Domain\Entity\Customer;
use App\Domain\Entity\CustomerOrder;
use App\Domain\Entity\Deposit;
use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    $product = $em->getReference(Product::class, $customerOrder->getProductId());
    $author = $em->getReference(User::class, $customerOrder->getAuthorId());
    
    $customerOrder
      ->setCustomer($customer)
      ->setDeposit($deposit)
      ->setProduct($product)
      ->setAuthor($author)
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
      ->setProductId($customerOrder->getProduct()->getId())
      ->setAuthorId($customerOrder->getAuthor()->getId())
    ;
  }
}