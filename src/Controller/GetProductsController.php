<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetProductsController
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    #[Route('/api/products', name: 'api_get_products', methods: 'GET')]
    public function __invoke(): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }
}