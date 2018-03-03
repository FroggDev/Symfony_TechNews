<?php

namespace App\Entity;

use App\Common\Util\String\SlugifyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
{
    use SlugifyTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $nameSlugified;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * For base64 encode
     * @ORM\Column(type="string",length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastConnexion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article",mappedBy="author")
     */
    private $articles;

    /**
     * Author constructor.
     */
    public function __construct()
    {
        # initialize date creation on Author creation
        $this->dateInscription = new \DateTime();

        # Initialize a news array collection
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
     * @param int $id
     * @return Author
     */
    public function setId(int $id): Author
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }


    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
        return $this->setManualyNameSlugified();
    }


    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Author
     */
    public function setLastName(string $lastName): Author
    {
        $this->lastName = $lastName;
        return $this->setManualyNameSlugified();
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Author
     */
    public function setEmail(string $email): Author
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return Author
     */
    public function setPassword(string $password): Author
    {
        $this->password = base64_encode($password);
        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getDateInscription(): \DateTime
    {
        return $this->dateInscription;
    }


    /**
     * @param \DateTime $dateInscription
     * @return Author
     */
    public function setDateInscription(\DateTime $dateInscription): Author
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }


    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return Author
     */
    public function setRoles(array $roles): Author
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getLastConnexion(): \DateTime
    {
        return $this->lastConnexion;
    }


    /**
     * @param \DateTime $lastConnexion
     * @return $this
     */
    public function setLastConnexion(\DateTime $lastConnexion)
    {
        $this->lastConnexion = $lastConnexion;
        return $this;
    }


    /**
     * return \Doctrine\ORM\PersistentCollection but init as ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param ArrayCollection $articles
     * @return Author
     */
    public function setArticles(ArrayCollection $articles): Author
    {
        $this->articles = $articles;
        return $this;
    }


    /**
     * @return string
     */
    public function getNameSlugified(): string
    {
        return $this->nameSlugified;
    }


    /**
     * @param string $nameSlugified
     * @return Author
     */
    public function setNameSlugified(string $nameSlugified): Author
    {
        $this->nameSlugified = $nameSlugified;
        return $this;
    }

    /**
     * @return Author
     */
    public function setManualyNameSlugified(): Author
    {
        $this->nameSlugified = $this->slugify($this->lastName . ' ' . $this->firstName);
        return $this;
    }
}
