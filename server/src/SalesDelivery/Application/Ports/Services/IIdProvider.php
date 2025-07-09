<?php 

namespace App\SalesDelivery\Application\Ports\Services;

interface IIdProvider {
  public function getId(): string;
}