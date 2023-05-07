<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\BasketRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Mapping\Entity(repositoryClass: BasketRepository::class)]
#[ORM\Mapping\Table(name: "baskets")]
class Basket
{
    #[ORM\Mapping\Id]
    #[ORM\Mapping\Column(name: "uuid", type: "uuid", unique: true, nullable: false)]
    private UuidInterface $uuid;

    #[ORM\Mapping\OneToMany(targetEntity: BasketItem::class, mappedBy: "basket")]
    private Collection $basketItems;

    #[ORM\Mapping\Column(name: "created_at", type: "carbon_date_time", nullable: false)]
    private ?CarbonInterface $createdAt;

    #[ORM\Mapping\Column(name: "checked_out_at", type: "carbon_date_time", nullable: true)]
    private ?CarbonInterface $checkedOutAt;

    #[ORM\Mapping\Column(name: "deleted_at", type: "carbon_date_time", nullable: true)]
    private ?CarbonInterface $deletedAt;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
        $this->createdAt = Carbon::now();
        $this->basketItems = new ArrayCollection();
    }

    public function deleteBasketItem(BasketItem $basketItem): void
    {
        $basketItem->delete();
    }

    public function checkOut(): void
    {
        $this->checkedOutAt = Carbon::now();
    }

    public function delete(): void
    {
        $this->deletedAt = Carbon::now();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getBasketItems(): Collection
    {
        return $this->basketItems;
    }

    public function getCreatedAt(): ?CarbonInterface
    {
        return $this->createdAt;
    }

    public function getCheckedOutAt(): ?CarbonInterface
    {
        return $this->checkedOutAt;
    }

    public function getDeletedAt(): ?CarbonInterface
    {
        return $this->deletedAt;
    }
}