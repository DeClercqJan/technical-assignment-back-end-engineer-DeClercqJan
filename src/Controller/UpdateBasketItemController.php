<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\BasketItem;
use App\Service\ValidationResultToResponse;
use Doctrine\ORM\EntityManagerInterface;
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateBasketItemController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/basket-items/{basketItem}', name: 'api_update_basket_item', methods: 'PATCH')]
    public function __invoke(Request $request, BasketItem $basketItem): JsonResponse
    {
        $input = json_decode($request->getContent(), true);
        $input['basket_item_uuid'] = $basketItem->getUuid();
        $result = $this->validateInput($input);
        if (false === $result->isValid()) {
            return ValidationResultToResponse::getResponse($result);
        }

        $basketItem->setAmount($input['amount']);
        $this->em->flush();

        return new JsonResponse(['uuid' => $basketItem->getUuid()->toString()], Response::HTTP_OK);
    }


    private function validateInput(array $data): ValidationResult
    {
        $validator = new Validator();
        $validator->required('amount')->integer();
        $validator->required('basket_item_uuid')->uuid()->callback(function($basketItemUuid) {
            return $this->em->getRepository(BasketItem::class)->count(['uuid' => $basketItemUuid]) > 0;
        });
        return $validator->validate($data);
    }
}
