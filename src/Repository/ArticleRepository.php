<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * ArticleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param int $nbMonth
     * @return array
     */
    public function findArticleFromLastMonths(int $nbMonth): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.dateCreation > DATE_ADD(NOW(),INTERVAL -'.$nbMonth.' MONTH)')
            ->orderBy('a.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.status LIKE :status')->setParameter('status' , "%published%")
            ->orderBy('a.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findLastFiveArticle(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $idCategory
     * @return array
     */
    public function findArticleSuggestions(int $idCategory): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.category = :category_id')->setParameter('category_id', $idCategory)
            ->andWhere('a.id != :article_id')->setParameter('article_id', $idCategory)
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findSpotLightArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = true')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findSpecialArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = true')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findCountArticles(): int
    {
        try {
            return $this->createQueryBuilder('a')
                ->select('count(a)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }


    /**
     * @return array
     */
    public function findAuthorArticlesByStatus(int $id,string $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.author = :id')->setParameter('id',$id)
            ->andWhere('a.status LIKE :status')->setParameter('status' , "%$status%")
            ->orderBy('a.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findArticlesByStatus(string $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.status LIKE :status')->setParameter('status' , "%$status%")
            ->orderBy('a.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function countAuthorArticlesByStatus(int $id,string $status)
    {
        try{
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->where('a.author = :id')->setParameter('id',$id)
                ->andWhere('a.status LIKE :status')->setParameter('status' , "%$status%")
                ->getQuery()
                ->getSingleScalarResult();
        }
        catch(NonUniqueResultException $e){
            return 0;
        }

    }

    /**
     * @return array
     */
    public function countArticlesByStatus(string $status)
    {
        try{
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->where('a.status LIKE :status')->setParameter('status' , "%$status%")
                ->getQuery()
                ->getSingleScalarResult();
        }
        catch(NonUniqueResultException $e){
            return 0;
        }

    }

}
