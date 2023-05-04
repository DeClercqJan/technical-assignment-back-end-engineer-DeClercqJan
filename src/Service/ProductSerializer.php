<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;

class ProductSerializer
{
    public static function serialize(Product $product): array
    {
        return [
            'uuid' => $product->getUuid()->toString(),
            'name' => $product->getName(),
            'price' => $product->getPriceInCents() / 100
        ];
    }

    public static function serializeList(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $result[] = self::serialize($product);
        }

        return $result;
    }
}
