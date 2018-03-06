<?php

namespace App\Entity;

use App\Common\Traits\String\SlugifyTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    use SlugifyTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $titleSlugified;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=150,nullable=true)
     */
    private $featuredImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $special;

    /**
     * @ORM\Column(type="boolean")
     */
    private $spotlight;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category",inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author",inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        # initialize date creation on Entity creation
        $this->dateCreation = new \DateTime();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return Article
     */
    public function setId(int $id): Article
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * @param $title
     * @return Article
     */
    public function setTitle($title): Article
    {
        $this->title = $title;
        return $this->setManualyTitleSlugified();
    }


    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }


    /**
     * @param string $content
     * @return Article
     */
    public function setContent(string $content): Article
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed (due to Symfony image upload)
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param mixed $featuredImage (due to Symfony image upload)
     * @return Article
     */
    public function setFeaturedImage($featuredImage): Article
    {
        $this->featuredImage = $featuredImage;
        return $this;
    }


    /**
     * @return bool|null
     */
    public function getSpecial(): ?bool
    {
        return $this->special;
    }


    /**
     * @param bool $special
     * @return Article
     */
    public function setSpecial(bool $special): Article
    {
        $this->special = $special;
        return $this;
    }


    /**
     * @return bool|null
     */
    public function getSpotlight(): ?bool
    {
        return $this->spotlight;
    }


    /**
     * @param bool $spotlight
     * @return Article
     */
    public function setSpotlight(bool $spotlight): Article
    {
        $this->spotlight = $spotlight;
        return $this;
    }


    /**
     * @return string
     */
    public function getDateCreation(): string
    {
        return $this->dateCreation->format('d/m/Y');
    }


    /**
     * @param \DateTime $dateCreation
     * @return Article
     */
    public function setDateCreation(\DateTime $dateCreation): Article
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }


    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Article
     */
    public function setCategory(Category $category): Article
    {
        $this->category = $category;
        return $this;
    }


    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Article
     */
    public function setAuthor(Author $author): Article
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitleSlugified(): string
    {
        return $this->titleSlugified;
    }


    /**
     * @param string $titleSlugified
     * @return Article
     */
    public function setTitleSlugified($titleSlugified): Article
    {
        $this->titleSlugified = $titleSlugified;
        return $this;
    }

    /**
     * @return Article
     */
    public function setManualyTitleSlugified(): Article
    {
        $this->titleSlugified = $this->slugify($this->title);
        return $this;
    }
}
