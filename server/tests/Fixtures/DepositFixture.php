<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\IDepositRepository;
use App\Domain\Entity\Deposit;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class DepositFixture implements IFixture {
  public function __construct(private readonly Deposit $deposit) {}

  public function load(Container $container): void {
    /**  @var IDepositRepository $customerRepository */
    $depositRepository = $container->get(IDepositRepository::class);
    $depositRepository->save($this->deposit);
  }
}