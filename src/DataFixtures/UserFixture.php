<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixture extends Fixture
{
    public const VALID_EMAIL = 'hat@monopoly.com';
    public const VALID_PASSWORD = 'hotels!';

    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User(
            UuidV4::uuid4(),
            UserFixture::VALID_EMAIL,
        );

        $user1->setPassword($this->passwordHasherFactory->getPasswordHasher($user1)->hash('hotels!'));
        $manager->persist($user1);

        $manager->flush();
    }
}