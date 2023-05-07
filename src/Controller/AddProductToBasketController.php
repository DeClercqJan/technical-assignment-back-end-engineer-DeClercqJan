<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Product;
use App\Service\ValidationResultToResponse;
use Doctrine\ORM\EntityManagerInterface;
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddProductToBasketController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/baskets/{basket}/add-product', name: 'api_add_product_to_basket', methods: 'POST')]
    public function __invoke(Request $request, Basket $basket): JsonResponse
    {
        $input = json_decode($request->getContent(), true);
        $result = $this->validateInput($input);
        if (false === $result->isValid()) {
            return ValidationResultToResponse::getResponse($result);
        }

        $product = $this->em->getRepository(Product::class)->find($input['product_uuid']);
        $uuid =  Uuid::uuid4();
        $basketItem = new BasketItem($uuid, $product, $input['amount'], $basket);
        $this->em->persist($basketItem);
        $this->em->flush();

        return new JsonResponse(['basket_item_uuid' => $uuid], Response::HTTP_OK);
    }

    private function validateInput(array $data): ValidationResult
    {
        $validator = new Validator();
        $validator->required('product_uuid')->uuid()->callback(function($productUuid) {
            return $this->em->getRepository(Product::class)->count(['uuid' => $productUuid]) > 0;
        });
        $validator->required('amount')->integer();

        return $validator->validate($data);
    }
}