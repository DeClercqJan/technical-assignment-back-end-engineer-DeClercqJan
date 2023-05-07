<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Mapping\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Mapping\Table(name: "products")]
class Product
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(name: "uuid", type: "uuid", unique: true, nullable: false)]
    private UuidInterface $uuid;

    #[ORM\Mapping\Column(name: "name", type: "string", unique: true, nullable: false)]
    private string $name;

    #[ORM\Mapping\Column(name: "price", type: "integer", nullable: false)]
    private int $price;

    public function __construct(UuidInterface $uuid, string $name, int $priceInCents)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->price = $priceInCents;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
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


