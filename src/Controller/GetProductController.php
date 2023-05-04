<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetProductController
{
    #[Route('/api/products/{product}', name: 'api_get_product', methods: 'GET')]
    public function __invoke(Product $product): JsonResponse
    {
        return new JsonResponse(
            ProductSerializer::serialize($product)
        );
    }
}