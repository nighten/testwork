<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Filter\ArticleFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param ArticleFilter $filter
     * @return array|Article[]
     */
    public function findByFilter(ArticleFilter $filter): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        if ($filter->getCategories() !== null) {
            $queryBuilder
                ->join('a.articleCategories', 'c')
                ->andWhere('c.category IN (:category)')
                ->setParameter('category', $filter->getCategories());
        }
        if ($filter->getText() !== null) {
            $queryBuilder
                ->andWhere('MATCH (a.title, a.text) AGAINST (:searchString) > 0')
                ->setParameter('searchString', $filter->getText());
        }
        if ($filter->getActive() !== null) {
            $queryBuilder->andWhere('a.active = :active')
                ->setParameter('active', $filter->getActive());
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
