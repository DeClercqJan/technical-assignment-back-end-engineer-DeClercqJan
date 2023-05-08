<?php
declare(strict_types=1);

namespace App\Controller;

    use App\Entity\BasketItem;
use App\Service\BasketItemSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetBasketItemsRemovedFromBasketBeforeCheckoutController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    #[Route('/api/get-basket-items-removed-from-basket-before-checkout', name: 'api_get_basket_items_removed_from_basket_before_checkout', methods: 'GET')]
    public function __invoke(Request $request): JsonResponse
    {
        $basketItems = $this->em->getRepository(BasketItem::class)->findRemovedBeforeCheckout();

        return new JsonResponse(
            BasketItemSerializer::serializeList($basketItems)
        );
    }
}