<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\ICustomerDeliveryRepository;
use App\Domain\Entity\CustomerDelivery;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class DeliveryFixture implements IFixture {
  public function __construct(private readonly CustomerDelivery $delivery) {}

  public function load(Container $container): void {
    /**  @var ICustomerDeliveryRepository $customerRepository */
    $deliveryRepository = $container->get(ICustomerDeliveryRepository::class);
    $deliveryRepository->save($this->delivery);
  }
}