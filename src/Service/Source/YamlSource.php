<?php

namespace App\Service\Source;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Service\Article\YamlProvider;
use App\SiteConfig;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class YamlSource
 * @package App\Service\Source
 */
class YamlSource extends ArticleAbstractSource
{

    private $yamlProvider;

    private $eManager;

    private $articles;

    # optimization for the find all
    private $convertedArticles;

    /**
     * YamlSource constructor.
     * @param YamlProvider $yamlProvider
     * @param EntityManagerInterface $eManager
     */
    public function __construct(YamlProvider $yamlProvider, EntityManagerInterface $eManager)
    {
        $this->eManager = $eManager;
        $this->yamlProvider = $yamlProvider;
        $this->articles = $this->yamlProvider->getArticles();
    }

    /**
     * Return an article from unique id
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        foreach ($this->articles as $tmpArticle) {
            if ($tmpArticle['id']) {
                break;
            }
        }

        /*
         * VERSION DU FORMATEUR ==>
         * ------------------------
        # Récupération de l'Article dans le tableau
        $articleId = array_column($this->articles, 'id');
        $key = array_search($id, $articleId);

        if(!$key) {
            return null;
        }

        # Ici, je récupère mon article depuis le tableau
        $tmp = (object) $this->articles['article'.++$key];
        */

        return $this->convertToArticle($tmpArticle);
    }

    /**
     * Return all articles
     * @return iterable
     */
    public function findAll(): iterable
    {
        # Optimization if already loaded
        if ($this->convertedArticles) {
            return $this->convertedArticles;
        }


        $this->convertedArticles = [];

        foreach ($this->articles as $tmpArticle) {
            $this->convertedArticles[] = $this->convertToArticle($tmpArticle);
        }

        usort($this->convertedArticles, "Self::sortByDate");

        return $this->convertedArticles;
    }


    /**
     * @param iterable $tmpArticle
     * @return Article|null
     */
    protected function convertToArticle(iterable $tmpArticle): ?Article
    {

        if (empty($tmpArticle)) {
            return null;
        }

        # init catagory
        $categories = new Category();

        # -------------------------------------------------------
        # ----- Get Category from article else insert in database
        # -------------------------------------------------------
        # get repo category
        $reposirotyCategory = $this->eManager->getRepository(Category::class);
        # get category from category
        $categoryFound = $reposirotyCategory
            ->getCategoryFromName($tmpArticle['category']['label']);
        # check if exist in doctrine
        if (!$categoryFound) {
            # Create new category if not exist in database
            $categories
                ->setId($tmpArticle['category']['id'])
                ->setLabel($tmpArticle['category']['label']);
            #send to database
            $this->eManager->persist($categories);
            $this->eManager->flush();
        } else {
            # set found category
            $categories = $categoryFound;
        }

        $author = new Author();
        $author
            ->setId($tmpArticle['author']['id'])
            ->setFirstName($tmpArticle['author']['firstname'])
            ->setLastName($tmpArticle['author']['lastname'])
            ->setEmail($tmpArticle['author']['email']);

        $date = date_create();
        date_timestamp_set($date, $tmpArticle['datecreation']);

        $article = new Article();
        $article
            ->setId($tmpArticle['id'])
            ->setTitle($tmpArticle['title'])
            ->setContent($tmpArticle['content'])
            ->setFeaturedImage($tmpArticle['featuredimage'])
            ->setAuthor($author)
            ->setCategory($categories)
            ->setSpecial($tmpArticle['special'])
            ->setSpotlight($tmpArticle['spotlight'])
            ->setDateCreation($date)
            ->setSource("YAML");

        return $article;
    }

    /**
     * Return last five articles
     * @return iterable|null
     */
    public function findLastFive(): ?iterable
    {
        $articles = $this->findAll();
        return array_slice($articles, 0, 5);
    }

    /**
     * Return spotlights articles
     * @return iterable|null
     */
    public function findSpotlights(): ?iterable
    {
        $articles = [];
        $nbFound = 0;
        $tmpArticles = $this->findAll();
        foreach ($tmpArticles as $article) {
            if ($article->getSpotlight()) {
                $articles[] = $article;
                $nbFound++;
            }

            if ($nbFound == SiteConfig::NBARTICLESPOTLIGHT) {
                break;
            }
        }
        return $articles;
    }

    /**
     * Return sugestion articles
     * @return iterable|null
     */
    public function findSugestions(): ?iterable
    {
        //@TODO :WORK
        return null;
    }

    /**
     * Return specials articles
     * @return iterable|null
     */
    public function findSpecials(): ?iterable
    {
        $articles = [];
        $nbFound = 0;
        $tmpArticles = $this->findAll();
        foreach ($tmpArticles as $article) {
            if ($article->getSpotlight()) {
                $articles[] = $article;
                $nbFound++;
            }

            if ($nbFound == SiteConfig::NBARTICLESPECIAL) {
                break;
            }
        }
        return $articles;
    }

    /**
     * get the number of items in each sources
     * @return int
     */
    public function count(): int
    {
        return count($this->articles);
    }

}
