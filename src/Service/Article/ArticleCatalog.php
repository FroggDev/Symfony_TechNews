<?php
namespace App\Service\Article;

use App\Entity\Article;
use App\Exception\DuplicateCatalogDataException;
use App\Service\Source\ArticleAbstractSource;
use App\SiteConfig;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class ArticleCatalog
 * @package App\Service\Article
 */
class ArticleCatalog implements ArticleCalatogInterface
{

    /**
     * @var iterable list of data sources from the mediator (Yaml, Mysql etc...)
     */
    private $sources;


    /**
     * Add a source
     * @param $source
     */
    public function addSource(ArticleAbstractSource $source): void
    {
        $this->sources[] = $source;
    }

    /**
     * List of srouces
     * @param iterable $sources
     */
    public function setSources(iterable $sources): void
    {
        $this->sources=$sources;
    }

    /**
     * Get a source
     * @return iterable|null
     */
    public function getSources(): ?iterable
    {
        return $this->sources;
    }


    /**
     * @param $functionToCall
     * @return ArrayCollection
     */
    private function sourcesUnifier($functionToCall,$functionSort=null) : iterable{
        $articles=[];

        foreach($this->sources as $source){
            foreach($source->$functionToCall() as $article){
                if(!array_key_exists($article->getId(),$articles)){
                    $articles[$article->getId()]=$article;
                }
            }
        }

        if($functionSort){
            usort($articles,"Self::$functionSort");
        }

        return new ArrayCollection($articles);
    }


    /**
     * Return an article from unique id
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        $articles=[];

        foreach ($this->sources as $source) {
            $article = $source->find($id);
            if ($article) {
                $articles[] = $article;
            }
        }

        if (count($articles)>1) {
            throw new DuplicateCatalogDataException(
                sprintf(
                    'Cannot return more than on record on line %s ' .
                    get_class($this).'::'.__FUNCTION__.'()',
                    __LINE__
                ),
                0,
                null,
                $articles[0]
            );
        }

        return array_pop($articles);
    }

    /**
     * Return all articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {

        return $this->sourcesUnifier('findAll');

/*
        $articles = [];

        foreach ($this->sources as $source) {
            $articles = array_merge(
                $articles,
                $source->findAll()
            );
        }

        # remove duplicated entries
        $idList = [];
        foreach ($articles as $k => $v) {
            if (in_array($v->getId(), $idList)) {
                unset($articles[$k]);
            } else {
                $idList[] = $v->getId();
            }
        }

        return new ArrayCollection($articles);
*/
    }


    /**
     * Return last five articles
     * @return iterable|null
     */
    public function findLastFive(): ?iterable
    {

        return $this->sourcesUnifier('findLastFive')->slice(0,5);

        /*
        $articles = $this->findAll()->toArray();

        usort(
            $articles,
            function ($a, $b)  {
            return $a->getDateCreation() > $b->getDateCreation();
        });

        return new ArrayCollection(array_slice($articles,0,5));
        */
    }


    /**
     * Return spotlights articles
     * @return iterable|null
     */
    public function findSpotlights(): ?iterable
    {
        return $this->sourcesUnifier('findSpotlights','sortByDate')->slice(0,SiteConfig::NBARTICLESPOTLIGHT);
    }

    /**
     * Return sugestion articles
     * @return iterable|null
     */
    public function findSugestions(): ?iterable
    {
        return $this->sourcesUnifier('findSugestions','sortByDate')->slice(0,SiteConfig::NBARTICLESUGESTION);
    }

    /**
     * Return specials articles
     * @return iterable|null
     */
    public function findSpecials(): ?iterable
    {
        return $this->sourcesUnifier('findSpecials','sortByDate')->slice(0,SiteConfig::NBARTICLESPECIAL);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function sortByDate($a, $b) : bool
    {
        return $a->getDateCreation() < $b->getDateCreation();
    }
}
