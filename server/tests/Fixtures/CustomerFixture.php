<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\ICustomerRepository;
use App\Domain\Entity\Customer;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class CustomerFixture implements IFixture {
  public function __construct(private readonly Customer $customer) {}

  public function load(Container $container): void {
    /**  @var ICustomerRepository $customerRepository */
    $customerRepository = $container->get(ICustomerRepository::class);
    $customerRepository->save($this->customer);
  }
}