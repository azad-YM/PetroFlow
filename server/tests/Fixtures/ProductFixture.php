<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\IProductRepository;
use App\Domain\Entity\Product;
use App\Tests\Infrastructure\IFixture;

class ProductFixture implements IFixture {
  public function __construct(private readonly Product $product) {}
  
  public function load(\Symfony\Component\DependencyInjection\Container $container): void {
    /**  @var IProductRepository $productRepository */
    $productRepository = $container->get(IProductRepository::class);
    $productRepository->save($this->product);
  }
}