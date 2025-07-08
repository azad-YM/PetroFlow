<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\IDriverRepostiory;
use App\Domain\Entity\Driver;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class DriverFixture implements IFixture {
  public function __construct(private readonly Driver $driver) {}

  public function load(Container $container): void {
    /**  @var IDriverRepostiory $driverRepository */
    $driverRepository = $container->get(IDriverRepostiory::class);
    $driverRepository->save($this->driver);
  }
}