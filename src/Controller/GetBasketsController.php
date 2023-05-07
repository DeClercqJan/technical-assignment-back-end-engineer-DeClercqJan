<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Service\BasketSerializer;
use App\Service\ValidationResultToResponse;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetBasketsController
{
    private EntityManagerInterface $em;

    private Validator $validator;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    #[Route('/api/baskets', name: 'api_get_baskets', methods: 'GET')]
    public function __invoke(Request $request): JsonResponse
    {
        $filters = $request->query->all();
        $result = $this->validateInput($filters);
        if (false === $result->isValid()) {
            return ValidationResultToResponse::getResponse($result);
        }

        $baskets = $this->em->getRepository(Basket::class)->findByFilters($filters);

        return new JsonResponse(
            BasketSerializer::serializeList($baskets)
        );
    }

    private function validateInput($data): ValidationResult
    {
        $this->validator = new Validator();

        $this->validator
            ->optional('created_from')
            ->datetime('d-m-Y H:i:s');

        $this->validator
            ->optional('created_until')
            ->datetime('d-m-Y H:i:s')
            ->callback(function ($value, $values) {
                $createdFrom = new Carbon($values['created_from']);
                $createdUntil = new Carbon($values['created_until']);

                if ($createdUntil->lte($createdFrom)) {
                    throw new \Particle\Validator\Exception\InvalidValueException(
                        'created_from should be greater than created_until',
                        'created_until'
                    );
                }

                return true;
            });

        $data = $this->allowNullableButCheckIfFromIsBeforeUntil($data, 'checked_out_from', 'checked_out_until');
        $data = $this->allowNullableButCheckIfFromIsBeforeUntil($data, 'basket_deleted_from', 'basket_deleted_until');
        $data = $this->allowNullableButCheckIfFromIsBeforeUntil($data, 'basket_items_deleted_from', 'basket_items_deleted_until');

        return $this->validator->validate($data);

    }

    private function allowNullableButCheckIfFromIsBeforeUntil(array $data, string $fromField, string $untilField): array
    {

        $this->validator
            ->optional($fromField);

        $this->validator
            ->optional($untilField)
            ->callback(function ($value, $values) use ($fromField, $untilField) {
                if ('false' === $values[$fromField]
                    || 'false' === $values[$untilField]
                ) {
                    return true;
                }

                $createdFrom = new Carbon($values[$fromField]);
                $createdUntil = new Carbon($values[$untilField]);
                if ($createdUntil->lte($createdFrom)) {
                    throw new \Particle\Validator\Exception\InvalidValueException(
                        sprintf('%s should be greater than %s', $createdFrom, $createdUntil),
                        $untilField
                    );
                }

                return true;
            });

        return $data;
    }
}