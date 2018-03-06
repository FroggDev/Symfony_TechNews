<?php

namespace App\Entity;

use App\Common\Traits\String\SlugifyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{

    use SlugifyTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $label;

    /**
     * @ORM\Column(type="string",length=50, unique=true)
     */
    private $labelSlugified;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param $id
     * @return Category
     */
    public function setId($id): Category
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }


    /**
     * @param string $label
     * @return Category
     */
    public function setLabel(string $label): Category
    {
        $this->label = $label;
        $this->labelSlugified = $this->slugify($this->label);
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelSlugified(): string
    {
        return $this->labelSlugified;
    }


    /**
     * @param string $labelSlugified
     * @return Category
     */
    public function setLabelSlugified(string $labelSlugified): Category
    {
        $this->labelSlugified = $labelSlugified;
        return $this;
    }

    /**
     * ...return \Doctrine\ORM\PersistentCollection but init as ArrayCollection but need array ...
     */
    public function getArticles()
    {
        return $this->articles;
    }


    /**
     * @param mixed $articles
     * @return Category
     */
    public function setArticles($articles): Category
    {
        $this->articles = $articles;
        return $this;
    }
}
