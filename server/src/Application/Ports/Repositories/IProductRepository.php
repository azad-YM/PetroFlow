<?php

namespace App\Application\Ports\Repositories;

use App\Domain\Entity\Product;

interface IProductRepository {
  public function findById(string $id): ?Product;
  public function save(Product $product): void;

}