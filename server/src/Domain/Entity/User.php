<?php

namespace App\Domain\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface {
  private string $email;
  private string $password;

  public function __construct(private string $id) {}

  public function getId(): string {
    return $this->id;
  }

  public function getPassword(): string|null {
    return $this->password;
  }

  public function setPassword(string $password) {
    $this->password = $password;
  }

  public function getRoles(): array {
    return ["ROLE_USER"];
  }

  public function getUserIdentifier(): string {
    return $this->email;
  }

  public function setEmail(string $email) {
    $this->email = $email;
  }

  public function eraseCredentials(): void {
    // nothing to do here
  }
}