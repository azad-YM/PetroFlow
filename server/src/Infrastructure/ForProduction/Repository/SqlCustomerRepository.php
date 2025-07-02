<?php

namespace App\Infrastructure\ForProduction\Repository;

use App\Application\Ports\Repositories\ICustomerRepository;
use App\Domain\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlCustomerRepository extends ServiceEntityRepository implements ICustomerRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Customer::class);
  }

  public function findById(string $id): ?Customer {
    return $this->find($id);
  }

  public function save(Customer $customer): void {
    $em = $this->getEntityManager();
    $em->persist($customer);
  }
}