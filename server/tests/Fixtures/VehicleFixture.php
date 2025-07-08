<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\IDriverRepostiory;
use App\Application\Ports\Repositories\IVehicleRepostiory;
use App\Domain\Entity\Driver;
use App\Domain\Entity\Vehicle;
use App\Tests\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class VehicleFixture implements IFixture {
  public function __construct(private readonly Vehicle $vehicle) {}

  public function load(Container $container): void {
    /**  @var IVehicleRepostiory $vehicleRepository */
    $vehicleRepository = $container->get(IVehicleRepostiory::class);
    $vehicleRepository->save($this->vehicle);
  }
}