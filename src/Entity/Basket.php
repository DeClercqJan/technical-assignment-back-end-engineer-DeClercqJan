<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\BasketRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ArrayCollection $basketItems;

    #[ORM\Mapping\Column(name: "checked_out_at", type: "carbon_date_time", nullable: true)]
    private CarbonInterface $checkedOutAt;

    #[ORM\Mapping\Column(name: "deleted_at", type: "carbon_date_time", nullable: true)]
    private ?CarbonInterface $deletedAt;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
        $this->basketItems = new ArrayCollection();
    }

    public function addBasketItem(BasketItem $basketItem): void
    {
        if (!$this->basketItems->contains($basketItem)) {
            $basketItem->setBasket($this);
            $this->basketItems[] = $basketItem;
        }
    }

    public function removeBasketItem(BasketItem $basketItem): void
    {
        $basketItem->delete();
    }

    public function checkOut(): void
    {
        $this->checkedOutAt = Carbon::now();
    }

    public function delete(BasketItem $basketItem): void
    {
        $this->deletedAt = Carbon::now();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getBasketItems(): ArrayCollection
    {
        return $this->basketItems;
    }

    public function getCheckedOutAt(): CarbonInterface
    {
        return $this->checkedOutAt;
    }

    public function getDeletedAt(): ?CarbonInterface
    {
        return $this->deletedAt;
    }
}