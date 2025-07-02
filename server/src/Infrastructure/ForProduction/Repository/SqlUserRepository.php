<?php

namespace App\Infrastructure\ForProduction\Repository;

use App\Application\Ports\Repositories\IUserRepository;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SqlUserRepository extends ServiceEntityRepository implements IUserRepository {
  public function __construct(\Doctrine\Persistence\ManagerRegistry $registry) {
    parent::__construct($registry, User::class);
  }

  public function save(User $user): void {
    $this->getEntityManager()->persist($user);
  }
}