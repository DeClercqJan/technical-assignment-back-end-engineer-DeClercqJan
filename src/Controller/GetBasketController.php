<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Service\BasketSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetBasketController
{
    #[Route('/api/baskets/{basket}', name: 'api_get_basket', methods: 'GET')]
    public function __invoke(Basket $basket): JsonResponse
    {
        return new JsonResponse(
            BasketSerializer::serialize($basket)
        );
    }
}
