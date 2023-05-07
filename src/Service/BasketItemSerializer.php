<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\BasketItem;

class BasketItemSerializer
{
    public static function serialize(BasketItem $basketItem): array
    {
        return [
            'uuid' => $basketItem->getUuid()->toString(),
            'basket_uuid' => $basketItem->getBasket()->getUuid()->toString(),
            'product' => ProductSerializer::serialize($basketItem->getProduct()),
            'amount' => $basketItem->getAmount(),
            'created_at' => $basketItem->getCreatedAt()->toString(),
            'deleted_at' => $basketItem->getDeletedAt()?->toString()
            ];
    }

    public static function serializeList(array $basketItems): array
    {
        $result = [];
        foreach ($basketItems as $basketItem) {
            $result[] = self::serialize($basketItem);
        }

        return $result;
    }
}