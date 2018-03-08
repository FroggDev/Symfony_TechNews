<?php
namespace App\Service\Article;

use App\Entity\Article;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Service\Article
 */
interface ArticleRepositoryInterface
{
    /**
     * Return an article from unique id
     * @param $id
     * @return Article|null
     */
    public function find($id) : ?Article;

    /**
     * Return all articles
     * @return iterable|null
     */
    public function findAll() : ?iterable;

    /**
     * Return last five articles
     * @return iterable|null
     */
    public function findLastFive() : ?iterable;

    /**
     * Return spotlights articles
     * @return iterable|null
     */
    public function findSpotlights() : ?iterable;

    /**
     * Return sugestion articles
     * @return iterable|null
     */
    public function findSugestions() : ?iterable;

    /**
     * Return specials articles
     * @return iterable|null
     */
    public function findSpecials() : ?iterable;

}
