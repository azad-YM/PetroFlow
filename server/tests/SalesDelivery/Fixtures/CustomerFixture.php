<?php

namespace App\Tests\SalesDelivery\Fixtures;

use App\SalesDelivery\Application\Ports\Repositories\ICustomerRepository;
use App\SalesDelivery\Domain\Entity\Customer;
use App\Tests\Shared\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class CustomerFixture implements IFixture {
  public function __construct(private readonly Customer $customer) {}

  public function load(Container $container): void {
    /**  @var ICustomerRepository $customerRepository */
    $customerRepository = $container->get(ICustomerRepository::class);
    $customerRepository->save($this->customer);
  }
}