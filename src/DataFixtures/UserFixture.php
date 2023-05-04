<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Rfc4122\UuidV4;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User(
            UuidV4::uuid4(),
            'hat@monopoly.com',
        );

        // hotels!
        $password = '$2y$13$pbcJYiEr08yxxQAg7fHasuaY6G6GxigK0k3RDuCGWE9wTMpY7KJke';
        $user1->setPassword($password);
        $manager->persist($user1);

        $manager->flush();
    }
}