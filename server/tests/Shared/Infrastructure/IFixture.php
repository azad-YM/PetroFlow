<?php

namespace App\Tests\Shared\Infrastructure;

use Symfony\Component\DependencyInjection\Container;

interface IFixture {
  public function load(Container $container): void;
}