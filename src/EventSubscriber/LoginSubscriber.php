<?php
namespace App\EventSubscriber;

#use Doctrine\ORM\EntityManagerInterface;
#use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginSubscriber
 * @package App\EventSubscriber
 */
class LoginSubscriber
{
    // Version not optimized !!!!

    private $eManager;

    private $session;

    /**
     * LoginSubscriber constructor.
     * @param EntityManagerInterface $eManager
     */
    public function __construct(EntityManagerInterface $eManager,SessionInterface $session)
    {
        $this->eManager = $eManager;
        $this->session = $session;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        # Get the User entity.
        $author = $event->getAuthenticationToken()->getUser();

        # set last connexion
        $author->setLastConnexion();

        # Persist the data to database. (Only if not exist in database)
        # $this->eManager->persist($author);
        $this->eManager->flush();


    }

}
