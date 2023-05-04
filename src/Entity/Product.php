<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

#[ORM\Mapping\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Mapping\Table(name: "products")]
class Product
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private string $uuid;

    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private string $name;

    #[ORM\Mapping\Column(type: "integer", nullable: false)]
    private int $price;


    public function __construct(UuidInterface $uuid, string $name, int $priceInCents)
    {
        $this->uuid = $uuid->toString();
        $this->name = $name;
        $this->price = $priceInCents;
    }

    public function getUuid(): UuidInterface
    {
        return UuidV4::fromString($this->uuid);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriceInCents(): int
    {
        return $this->price;
    }
}


