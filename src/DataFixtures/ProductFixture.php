<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

class ProductFixture extends Fixture
{
    private ObjectManager $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $product1 = ProductFixture::createProduct(UuidV4::uuid4(), 'Pioneer DJ Mixer', 69900);
        $product2 = ProductFixture::createProduct(UuidV4::uuid4(), 'Roland Wave Sampler', 48500);
        $product3 = ProductFixture::createProduct(UuidV4::uuid4(), 'Reloop Headphone', 15900);
        $product4 = ProductFixture::createProduct(UuidV4::uuid4(), 'Rokit Monitor', 18990);
        $product5 = ProductFixture::createProduct(UuidV4::uuid4(), 'Fisherprice Baby Mixer', 12000);

        $this->manager->persist($product1);
        $this->manager->persist($product2);
        $this->manager->persist($product3);
        $this->manager->persist($product4);
        $this->manager->persist($product5);
        $this->manager->flush();
    }

    public static function createProduct(UuidInterface $uuid, string $name, int $priceInCents): Product {
         return new Product(
            $uuid,
            $name,
            $priceInCents
        );
    }
}