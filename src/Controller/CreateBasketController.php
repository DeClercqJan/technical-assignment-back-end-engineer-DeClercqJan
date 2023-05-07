<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateBasketController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ){
        $this->em = $em;
    }

    #[Route('/api/baskets', name: 'api_create_basket', methods: 'POST')]
    public function __invoke(Request $request): JsonResponse
    {
        $uuid = UuidV4::uuid4();

        $newBasket = new Basket($uuid);

        $this->em->persist($newBasket);
        $this->em->flush();

        return new JsonResponse(['uuid' => $uuid], Response::HTTP_CREATED);
    }
}