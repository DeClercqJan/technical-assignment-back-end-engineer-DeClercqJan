<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Mapping\Entity(repositoryClass: UserRepository::class)]
#[ORM\Mapping\Table(name: "users")]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private string $uuid;

    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private string $email;

    #[ORM\Mapping\Column(type: "string", nullable: false)]
    private string $password;

    public function __construct(UuidInterface $uuid, string $email)
    {
        $this->uuid = $uuid->toString();
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUuid(): UuidInterface
    {
        return UuidV4::fromString($this->uuid);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}