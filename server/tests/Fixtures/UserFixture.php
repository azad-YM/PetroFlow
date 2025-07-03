<?php

namespace App\Tests\Fixtures;

use App\Application\Ports\Repositories\IUserRepository;
use App\Domain\Entity\User;
use App\Tests\Infrastructure\IFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserFixture implements IFixture {
  public function __construct(private readonly User $user) {}

  public function load(Container $container): void {
    $factory = new PasswordHasherFactory([
      User::class => ['algorithm' => 'auto'],
    ]);

    $passwordHasher = new UserPasswordHasher($factory);
    $clearPassword = $this->user->getPassword();

    $hashedPassword = $passwordHasher->hashPassword($this->user, $clearPassword);
    $this->user->setPassword($hashedPassword);

    /**  @var IUserRepository $userRepository */
    $userRepository = $container->get(IUserRepository::class);
    $userRepository->save($this->user);
  }

  public function authenticate(KernelBrowser $browser) {
    $browser->loginUser($this->user);
  }
}