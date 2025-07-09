<?php

namespace App\Tests\SalesDelivery\Fixtures;

use App\SalesDelivery\Application\Ports\Repositories\IProductRepository;
use App\SalesDelivery\Domain\Entity\Product;
use App\Tests\Shared\Infrastructure\IFixture;

class ProductFixture implements IFixture {
  public function __construct(private readonly Product $product) {}
  
  public function load(\Symfony\Component\DependencyInjection\Container $container): void {
    /**  @var IProductRepository $productRepository */
    $productRepository = $container->get(IProductRepository::class);
    $productRepository->save($this->product);
  }
}