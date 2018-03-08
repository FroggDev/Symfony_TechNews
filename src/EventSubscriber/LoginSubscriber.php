<?php
namespace App\EventSubscriber;

#use Doctrine\ORM\EntityManagerInterface;
#use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginSubscriber
 * @package App\EventSubscriber
 */
class LoginSubscriber
{
    // Version not optimized !!!!
/*
    private $eManager;

    public function __construct(EntityManagerInterface $eManager)
    {
        $this->eManager = $eManager;
    }

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
*/
}
