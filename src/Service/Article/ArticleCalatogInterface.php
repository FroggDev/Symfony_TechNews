<?php
namespace App\Service\Article;

use App\Service\Source\ArticleAbstractSource;

/**
 * Mediator interface
 * Interface ArticleCalatogInterface
 * @package App\Service\Article
 */
interface ArticleCalatogInterface extends ArticleRepositoryInterface
{
    /**
     * Add a source
     * @param $source
     */
    public function addSource(ArticleAbstractSource $source) : void;

    /**
     * List of sources
     * @param iterable $sources
     */
    public function setSources(iterable $sources) : void;

    /**
     * Get a source
     * @return iterable|null
     */
    public function getSources() : ?iterable;

    /**
     * Sort an array by key date
     * @param $a
     * @param $b
     * @return bool
     */
    public function sortByDate($a, $b): bool;
}
