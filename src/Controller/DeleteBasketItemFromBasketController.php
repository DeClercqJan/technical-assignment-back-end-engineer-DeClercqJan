<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBasketItemFromBasketController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/baskets/{basket}/basket-items/{basketItem}', name: 'api_delete_basket_item_from_basket', methods: 'DELETE')]
    public function __invoke(Request $request, Basket $basket, BasketItem $basketItem): JsonResponse
    {
        $basket->deleteBasketItem($basketItem);
        $this->em->flush();

        return new JsonResponse([], Response::HTTP_OK);
    }
}