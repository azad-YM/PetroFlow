<?php

namespace App\SalesDelivery\Infrastructure\ForProduction\Repository;

use App\SalesDelivery\Application\Ports\Repositories\IProductRepository;
use App\SalesDelivery\Domain\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SqlProductRepository extends ServiceEntityRepository implements IProductRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Product::class);
  }

  public function findById(string $id): ?Product {
    return $this->find($id);
  }

  public function save(Product $product): void {
    $em = $this->getEntityManager();
    $em->persist($product);
  }
}