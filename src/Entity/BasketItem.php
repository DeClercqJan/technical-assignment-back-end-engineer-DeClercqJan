<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\BasketItemRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Mapping\Entity(repositoryClass: BasketItemRepository::class)]
#[ORM\Mapping\Table(name: "basket_items")]
class BasketItem
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(name: "uuid", type: "uuid", unique: true, nullable: false)]
    private UuidInterface $uuid;

    #[ORM\Mapping\OneToOne(targetEntity: Product::class)]
    #[ORM\Mapping\JoinColumn(name: "product_uuid", referencedColumnName: "uuid", nullable: false)]
    private Product $product;

    #[ORM\Mapping\ManyToOne(targetEntity: Basket::class, inversedBy: "basketItems")]
    #[ORM\Mapping\JoinColumn(name: "basket_uuid", referencedColumnName: "uuid", nullable: false)]
    private Basket $basket;

    #[ORM\Mapping\Column(name: "created_at", type: "carbon_date_time", nullable: false)]
    private CarbonInterface $createdAt;

    #[ORM\Mapping\Column(name: "deleted_at", type: "carbon_date_time", nullable: true)]
    private ?CarbonInterface $deletedAt;

    public function __construct(UuidInterface $uuid, Product $product)
    {
        $this->uuid = $uuid;
        $this->product = $product;
        $this->createdAt = Carbon::now();
    }

    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }

    public function delete(): void
    {
        $this->deletedAt = Carbon::now();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?CarbonInterface
    {
        return $this->deletedAt;
    }
}