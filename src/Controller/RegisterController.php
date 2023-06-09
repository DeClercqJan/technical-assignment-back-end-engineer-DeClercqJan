<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\ValidationResultToResponse;
use Doctrine\ORM\EntityManagerInterface;
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController
{
    private EntityManagerInterface $em;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ){
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function __invoke(Request $request): JsonResponse
    {
        $input = json_decode($request->getContent(), true);
        $result = $this->validateInput($input);

        if (false === $result->isValid()) {
            return ValidationResultToResponse::getResponse($result);
        }

        $uuid =  Uuid::uuid4();
        $newUser = new User(
            $uuid,
            $input['email'],
        );
        $passwordHash = $this->passwordHasher->hashPassword($newUser, $input['password']);
        $newUser->setPassword($passwordHash);

        $this->em->persist($newUser);
        $this->em->flush();

        return new JsonResponse(['uuid' => $uuid], Response::HTTP_CREATED);
    }

    private function validateInput(array $data): ValidationResult
    {
        $validator = new Validator();
        $validator->required('email')->email();
        $validator->required('password')->string();

        return $validator->validate($data);
    }
}