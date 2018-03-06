<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Newsletter")
 */
class Newsletter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSubscribe;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateUnSubscribe;

    public function __construct()
    {
        $this->dateSubscribe=new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Newsletter
     */
    public function setId($id): Newsletter
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return Newsletter
     */
    public function setEmail($email): Newsletter
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUnSubscribe()
    {
        return $this->dateUnSubscribe;
    }

    /**
     * @param \DateTime $dateUnSubscribe
     * @return Newsletter
     */
    public function setDateUnSubscribe(\DateTime $dateUnSubscribe)
    {
        $this->dateUnSubscribe = $dateUnSubscribe;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateSubscribe()
    {
        return $this->dateSubscribe;
    }

    /**
     * @param \DateTime $dateSubscribe
     * @return Newsletter
     */
    public function setDateSubscribe(\DateTime $dateSubscribe)
    {
        $this->dateSubscribe = $dateSubscribe;
        return $this;
    }
}
