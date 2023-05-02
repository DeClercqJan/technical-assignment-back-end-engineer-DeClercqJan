<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Mapping\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Mapping\Table(name: "products")]
class Product
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private UuidInterface $uuid;

    #[ORM\Mapping\Column(type: "string", unique: true, nullable: false)]
    private string $name;

    #[ORM\Mapping\Column(type: "integer", nullable: false)]
    private int $price;


    public function __construct(UuidInterface $uuid, string $name, int $price)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->price = $price;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}


