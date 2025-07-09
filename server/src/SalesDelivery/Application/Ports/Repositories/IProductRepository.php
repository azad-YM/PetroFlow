<?php

namespace App\SalesDelivery\Application\Ports\Repositories;

use App\SalesDelivery\Domain\Entity\Product;

interface IProductRepository {
  public function findById(string $id): ?Product;
  public function save(Product $product): void;

}