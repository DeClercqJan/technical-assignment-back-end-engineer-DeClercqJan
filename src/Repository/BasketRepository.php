<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Basket;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('basket');

        if (array_key_exists('created_from', $filters)) {
            $createdFrom = new Carbon($filters['created_from']);
            $qb
                ->andWhere($qb->expr()->gte('basket.createdAt', ':createdFrom'))
                ->setParameter('createdFrom', $createdFrom->format('Y-m-d H:i:s'));
        }

        if (array_key_exists('created_until', $filters)) {
            $createdUntil = new Carbon($filters['created_until']);
            $qb
                ->andWhere($qb->expr()->lte('basket.createdAt', ':createdUntil'))
                ->setParameter('createdUntil', $createdUntil->format('Y-m-d H:i:s'));
        }

        if (
            array_key_exists('checked_out_from', $filters) && 'false' === $filters['checked_out_from']
            || array_key_exists('checked_out_until', $filters) && 'false' === $filters['checked_out_until']
        ) {
            $qb
                ->andWhere($qb->expr()->isNull('basket.checkedOutAt'));
        } else {
            if (array_key_exists('checked_out_from', $filters)) {
                $checkedOutFrom = new Carbon($filters['checked_out_from']);
                $qb
                    ->andWhere($qb->expr()->gte('basket.checkedOutAt', ':checkedOutFrom'))
                    ->setParameter('checkedOutFrom', $checkedOutFrom->format('Y-m-d H:i:s'));
            }

            if (array_key_exists('checked_out_until', $filters)) {
                $checkedOutUntil = new Carbon($filters['checked_out_until']);
                $qb
                    ->andWhere($qb->expr()->lte('basket.checkedOutAt', ':checkedOutUntil'))
                    ->setParameter('checkedOutUntil', $checkedOutUntil->format('Y-m-d H:i:s'));
            }
        }

        if (
            array_key_exists('basket_deleted_from', $filters) && 'false' === $filters['basket_deleted_from']
            || array_key_exists('basket_deleted_until', $filters) && 'false' === $filters['basket_deleted_until']
        ) {
            $qb
                ->andWhere($qb->expr()->isNull('basket.deletedAt'));
        } else {
            if (array_key_exists('basket_deleted_from', $filters)) {
                $basketDeletedFrom = new Carbon($filters['basket_deleted_from']);
                $qb
                    ->andWhere($qb->expr()->gte('basket.deletedAt', ':basketDeletedFrom'))
                    ->setParameter('basketDeletedFrom', $basketDeletedFrom->format('Y-m-d H:i:s'));
            }

            if (array_key_exists('basket_deleted_until', $filters)) {
                $basketDeletedUntil = new Carbon($filters['basket_deleted_until']);
                $qb
                    ->andWhere($qb->expr()->lte('basket.deletedAt', ':basketDeletedUntil'))
                    ->setParameter('basketDeletedUntil', $basketDeletedUntil->format('Y-m-d H:i:s'));
            }
        }


        if (
            array_key_exists('basket_items_deleted_from', $filters) && 'false' === $filters['basket_items_deleted_from']
            || array_key_exists('basket_items_deleted_until', $filters) && 'false' === $filters['basket_deleted_until']
        ) {
            $qb
                ->join('basket.basketItems', 'basketItems')
                ->andWhere($qb->expr()->isNull('basketItems.deletedAt'));
        } else {

            if (array_key_exists('basket_items_deleted_from', $filters)) {
                $basketItemsDeletedFrom = new Carbon($filters['basket_items_deleted_from']);
                $qb
                    ->join('basket.basketItems', 'basketItems')
                    ->andWhere($qb->expr()->gte('basketItems.deletedAt', ':basketItemsDeletedFrom'))
                    ->setParameter('basketItemsDeletedFrom', $basketItemsDeletedFrom->format('Y-m-d H:i:s'));
            }

            if (array_key_exists('basket_items_deleted_until', $filters)) {
                $basketItemsDeletedUntil = new Carbon($filters['basket_items_deleted_until']);
                $qb
                    ->join('basket.basketItems', 'basketItems2')
                    ->andWhere($qb->expr()->lte('basketItems.deletedAt', ':basketItemsDeletedUntil'))
                    ->setParameter('basketItemsDeletedUntil', $basketItemsDeletedUntil->format('Y-m-d H:i:s'));
            }
        }

        if (array_key_exists('limit', $filters) && 'false' !== $filters['limit']) {
            $qb->setMaxResults($filters['limit']);
        }

        if (array_key_exists('offset', $filters) && 'false' !== $filters['offset']) {
            $qb->setFirstResult($filters['offset']);
        }

        if (array_key_exists('sort_by', $filters) && array_key_exists('sort_direction', $filters)) {
            $qb->orderBy(sprintf('basket.%s', $filters['sort_by']), $filters['sort_direction']);
        } else {
            $qb->orderBy('basket.createdAt', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }
}