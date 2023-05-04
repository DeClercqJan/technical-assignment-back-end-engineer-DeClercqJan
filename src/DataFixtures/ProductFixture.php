<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Rfc4122\UuidV4;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product1 = new Product(
            UuidV4::uuid4(),
            'Priceless widget',
        1400,
        );

        $product2 = new Product(
            UuidV4::uuid4(),
            'Overprices junk',
            3333,
        );

        $manager->persist($product1);
        $manager->persist($product2);

        $manager->flush();
    }
}