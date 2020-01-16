<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Filter\CategoryFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByFilter(CategoryFilter $filter): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        if ($filter->getActive() !== null) {
            $queryBuilder->where('c.active = :active')
                ->setParameter('active', $filter->getActive());
        }
        return $queryBuilder->getQuery()->getResult();
    }
}