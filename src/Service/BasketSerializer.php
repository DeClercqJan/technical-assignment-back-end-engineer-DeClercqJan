<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;

class BasketSerializer
{
    public static function serialize(Basket $basket): array
    {
        $result = [
            'uuid' => $basket->getUuid()->toString(),
            'checked_out_at' => $basket->getCheckedOutAt()?->toString(),
            'deleted_at' => $basket->getDeletedAt()?->toString(),
            'basket_items' => []
        ];

        foreach ($basket->getBasketItems() as $basketItem) {
            $result['basket_items'][] = BasketItemSerializer::serialize($basketItem);
        }

        return $result;
    }

    public static function serializeList(array $baskets): array
    {
        $result = [];
        foreach ($baskets as $basket) {
            $result[] = self::serialize($basket);
        }

        return $result;
    }
}