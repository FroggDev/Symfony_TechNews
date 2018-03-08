<?php
namespace App\Service\Source;

use App\Entity\Article;
use App\Service\Article\ArticleCatalog;
use App\Service\Article\ArticleRepositoryInterface;

/**
 * Class ArticleAbstractSource
 * @package App\Service\Source
 */
abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{

    private $catalog;

    /**
     * @param ArticleCatalog $catalog
     */
    public function setCatalog(ArticleCatalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @param iterable $tmpArticle
     * @return Article|null
     */
    abstract protected  function convertToArticle(iterable $tmpArticle): ?Article;

}
