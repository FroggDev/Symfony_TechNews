<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLastFiveArticle()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findArticleSuggestions($idArticle, $idCategory)
    {
        return $this->createQueryBuilder('a')
            ->where('a.category = :category_id')->setParameter('category_id', $idCategory)
            ->andWhere('a.id != :article_id')->setParameter('article_id', $idCategory)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findSpotLightArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = true')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findSpecialArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = true')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
