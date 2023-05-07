<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckOutBasketController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/baskets/{basket}/check-out', name: 'api_check_out_basket', methods: 'POST')]
    public function __invoke(Request $request, Basket $basket): JsonResponse
    {
        $basket->checkOut();
        $this->em->flush();

        return new JsonResponse([], Response::HTTP_OK);
    }
}