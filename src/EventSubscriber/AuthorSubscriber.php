<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/03/2018
 * Time: 10:34
 */

namespace App\EventSubscriber;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class AuthorSubscriber
 * @package App\EventSubscriber
 */
class AuthorSubscriber implements EventSubscriberInterface
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

        if ($author instanceof Author) {
            # set last connexion
            $author->setLastConnexion();
            # Persist the data to database.
            # $this->eManager->persist($author);
            # sauvegarde en database
            $this->eManager->flush();
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin'
        ];
    }
}
