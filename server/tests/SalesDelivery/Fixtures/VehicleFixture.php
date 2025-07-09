<?php

namespace App\Tests\SalesDelivery\Fixtures;

use App\SalesDelivery\Application\Ports\Repositories\IVehicleRepostiory;
use App\SalesDelivery\Domain\Entity\Vehicle;
use App\Tests\Shared\Infrastructure\IFixture;
use Symfony\Component\DependencyInjection\Container;

class VehicleFixture implements IFixture {
  public function __construct(private readonly Vehicle $vehicle) {}

  public function load(Container $container): void {
    /**  @var IVehicleRepostiory $vehicleRepository */
    $vehicleRepository = $container->get(IVehicleRepostiory::class);
    $vehicleRepository->save($this->vehicle);
  }
}