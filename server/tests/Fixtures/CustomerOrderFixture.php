<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\ICustomerOrderRepository;
use App\Domain\Entity\CustomerOrder;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class CustomerOrderFixture implements IFixture {
  public function __construct(private readonly CustomerOrder $order) {}

  public function load(Container $container): void {
    /**  @var ICustomerOrderRepository $orderRepository */
    $orderRepository = $container->get(ICustomerOrderRepository::class);
    $orderRepository->save($this->order);
  }
}