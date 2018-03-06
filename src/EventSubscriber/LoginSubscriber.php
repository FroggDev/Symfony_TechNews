<?php
namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;

/**
 * Class LoginSubscriber
 * @package App\EventSubscriber
 */
class LoginSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     * LoginSubscriber constructor.
     * @param EntityManagerInterface $eManager
     */
    public function __construct(EntityManagerInterface $eManager)
    {
        $this->eManager = $eManager;
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

        # Persist the data to database.
        $this->eManager->persist($author);
        $this->eManager->flush();
    }
}
