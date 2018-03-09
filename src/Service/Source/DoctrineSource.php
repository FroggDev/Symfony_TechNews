<?php

namespace App\Service\Source;

use App\Entity\Article;
use App\SiteConfig;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineSource
 * @package App\Service\Source
 */
class DoctrineSource extends ArticleAbstractSource
{

    private $eManager;

    private $entity = Article::class;


    /**
     * DoctrineSource constructor.
     * @param EntityManagerInterface $eManager
     */
    public function __construct(EntityManagerInterface $eManager)
    {
        $this->eManager = $eManager;
    }

    /**
     * Return an article from unique id
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->find($id);
    }

    /**
     * Return all articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->findAll();
    }

    /**
     * Return last five articles
     * @return iterable|null
     */
    public function findLastFive(): ?iterable
    {
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->createQueryBuilder('a')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return spotlights articles
     * @return iterable|null
     */
    public function findSpotlights(): ?iterable
    {
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->createQueryBuilder('a')
            ->where('a.spotlight = true')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(SiteConfig::NBARTICLESPOTLIGHT)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return sugestion articles
     * @return iterable|null
     */
    public function findSugestions(): ?iterable
    {
        return null; //@TODO : WORK
        /*
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->createQueryBuilder('a')
            ->where('a.category = :category_id')->setParameter('category_id', $idCategory)
            ->andWhere('a.id != :article_id')->setParameter('article_id', $idCategory)
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(SiteConfig::NBARTICLESUGESTION)
            ->getQuery()
            ->getResult();*/
    }

    /**
     * Return specials articles
     * @return iterable|null
     */
    public function findSpecials(): ?iterable
    {
        return $this
            ->eManager
            ->getRepository($this->entity)
            ->createQueryBuilder('a')
            ->where('a.special = true')
            ->orderBy('a.dateCreation', 'DESC')
            ->setMaxResults(SiteConfig::NBARTICLESPECIAL)
            ->getQuery()
            ->getResult();
    }

    /*======================================*/
    /* THOOSE ARE NOT REQUIRED FOR DOCTRINE */
    /*======================================*/

    /**
     * @param iterable $tmpArticle
     * @return Article|null
     */
    protected function convertToArticle(iterable $tmpArticle): ?Article
    {
        return null;
    }
}
