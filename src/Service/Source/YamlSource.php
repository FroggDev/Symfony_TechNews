<?php

namespace App\Service\Source;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Service\Article\YamlProvider;
use App\SiteConfig;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class YamlSource
 * @package App\Service\Source
 */
class YamlSource extends ArticleAbstractSource
{

    private $yamlProvider;

    private $eManager;

    private $articles;

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
         * @TODO APRES C ESTMOI QUI SUIS CRITIQUE
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
        $articles = [];

        foreach ($this->articles as $tmpArticle) {
            $articles[] = $this->convertToArticle($tmpArticle);
        }

        return $articles;
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
            ->setDateCreation($date);

        return $article;
    }

    /**
     * Return last five articles
     * @return iterable|null
     */
    public function findLastFive(): ?iterable
    {
        return array_slice($this->sortByDate($this->findAll()), 0, 5);
    }

    /**
     * Return spotlights articles
     * @return iterable|null
     */
    public function findSpotlights(): ?iterable
    {
        $articles = [];
        $nbFound = 0;
        $tmpArticles = $this->sortByDate($this->findAll());
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
        $tmpArticles = $this->sortByDate($this->findAll());
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
     * @param array $articles
     * @return array
     */
    private  function sortByDate(Array $articles)
    {
        usort(
            $articles,
            function ($a, $b) {
                return $a->getDateCreation() > $b->getDateCreation();
            });
        return $articles;
    }
}
