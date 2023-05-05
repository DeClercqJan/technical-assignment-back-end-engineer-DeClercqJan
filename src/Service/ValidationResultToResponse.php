<?php
declare(strict_types=1);

namespace App\Service;

use Particle\Validator\ValidationResult;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationResultToResponse
{
    public static function getResponse(ValidationResult $result): JsonResponse
    {
        $errors = [];
        foreach ($result->getMessages() as $message) {
            $errors[] = array_values($message)[ 0 ];
        }

        return new JsonResponse(
            [
                'code' => JsonResponse::HTTP_BAD_REQUEST,
                'message' => $errors],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }
}