<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\BasketItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BasketItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasketItem::class);
    }

    public function findRemovedBeforeCheckout(): array
    {
        $qb = $this->createQueryBuilder('basketItem');

        $qb->join('basketItem.basket', 'basket')
            ->andWhere($qb->expr()->isNotNull('basketItem.deletedAt'))
            ->andWhere($qb->expr()->isNotNull('basket.checkedOutAt'))
            ->andWhere($qb->expr()->lt('basketItem.deletedAt', 'basket.checkedOutAt'))
        ;

        return $qb->getQuery()->getResult();
    }
}