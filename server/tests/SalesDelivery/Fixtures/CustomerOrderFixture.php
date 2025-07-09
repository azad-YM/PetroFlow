<?php

namespace App\Tests\SalesDelivery\Fixtures;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerOrderRepository;
use App\SalesDelivery\Domain\Entity\CustomerOrder;
use App\Tests\Shared\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class CustomerOrderFixture implements IFixture {
  public function __construct(private readonly CustomerOrder $order) {}

  public function load(Container $container): void {
    /**  @var ICustomerOrderRepository $orderRepository */
    $orderRepository = $container->get(ICustomerOrderRepository::class);
    $orderRepository->save($this->order);
  }
}