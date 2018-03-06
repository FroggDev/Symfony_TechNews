<?php

namespace App\Entity;

use App\Common\Traits\String\SlugifyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 * @UniqueEntity(fields={"email"},errorPath="email",message="This email is already in use")
 * @see https://symfony.com/doc/current/reference/constraints/UniqueEntity.html
 */
class Author implements AdvancedUserInterface
{
    use SlugifyTrait;

    #####################
    # Account constants #
    #####################

    /**
     * Constant for inactive Author, register but didn't validate email confirmation
     */
    const INACTIVE = 0;
    /**
     * Constant for registerd Author
     */
    const ACTIVE = 1;
    /**
     * Constant for Author closed account
     */
    const CLOSED = 2;
    /**
     * Constant for Author banned account
     */
    const BANNED = 3;
    /**
     * Constant for Token validity time in day
     */
    const TOKENVALIDITYTIME = 1; #day

    ##########
    # Entity #
    ##########

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
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $tokenValidity;

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
     * Author account state ( 0 = inactive ; 1 = active ; 2 = closed ; 3 = banned ...other state for later )
     * @ORM\Column(type="integer")
     * @see Author contants
     */
    private $status;

    /**
     * When Author account is closed or banned
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateClosed;


    ###########
    # Methods #
    ###########


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
    public function getFirstName(): ?string
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
    public function getLastName(): ?string
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
    public function getEmail(): ?string
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
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return Author
     */
    public function setPassword(string $password): Author
    {
        $this->password = $password;
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
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     * @return Author
     */
    public function setRoles(string $role): Author
    {
        $this->roles[] = $role;
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
     * @param mixed $articles
     * @return Author
     */
    public function setArticles($articles): Author
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

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Author
     */
    public function setToken(): Author
    {
        //$author->setToken(bin2hex(random_bytes(100)));
        $this->token = uniqid('', true) . uniqid('', true);
        #set token validity only if account has been validated
        # case if user didnt validated email, can validate later
        if ($this->status !== $this::INACTIVE) {
            $this->tokenValidity = new \DateTime();
        }
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTokenValidity(): \DateTime
    {
        return $this->tokenValidity;
    }

    public function isTokenExpired(): bool
    {
        if ($this->tokenValidity==null) {
            return false;
        }
        $now = new \DateTime();
        return $now->diff($this->getTokenValidity())->days > $this::TOKENVALIDITYTIME;
    }

    /**
     * @return Author
     */
    public function removeToken(): Author
    {
        $this->tokenValidity = null;
        $this->token = null;
        return $this;
    }


    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     * @return Author
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }


    /**
     * @return Author
     */
    public function setInactive(): Author
    {
        $this->status = $this::INACTIVE;
        return $this;
    }

    /**
     * @return Author
     */
    public function setActive(): Author
    {
        $this->status = $this::ACTIVE;
        return $this;
    }

    /**
     * @return Author
     */
    public function setClosed(): Author
    {
        $this->status = $this::CLOSED;
        $this->setDateClosed();
        return $this;
    }

    /**
     * @return Author
     */
    public function setBanned(): Author
    {
        $this->status = $this::BANNED;
        $this->setDateClosed();
        return $this;
    }

    /**
     * @return bool
     */
    public function isBanned() : bool
    {
        return $this->status == $this::BANNED;
    }

    /**
     * @return bool
     */
    public function isClosed() : bool
    {
        return $this->status == $this::CLOSED;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->status == $this::ACTIVE;
    }

    /**
     * @return void
     */
    private function setDateClosed(): void
    {
        $this->dateClosed = new \DateTime();
    }


    /**
     * @return \DateTime()
     */
    public function getDateClosed(): \DateTime
    {
        return $this->dateClosed;
    }


    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }
}
