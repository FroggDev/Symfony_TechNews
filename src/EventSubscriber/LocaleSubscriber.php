<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 09/03/2018
 * Time: 12:51
 */

namespace App\EventSubscriber;

use App\SiteConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleSubscriber
 * @package App\EventSubscriber
 */
class LocaleSubscriber implements EventSubscriberInterface
{

    /**
     * get the user browser language
     * @return string|null
     * @access private
     */
    private function getUserBrowserLangs() : ?string
    {
        preg_match_all('/([a-z]{2})-[A-Z]{2}/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang);
        if (count($lang)>0) {
            return $lang[1][0];
        }
        return null;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {

        # get the request
        $request = $event->getRequest();

        # check if lang are set as arguments, then check in cookies
        $currentLang = $_COOKIE["lang"]??$this->getUserBrowserLangs()??$request->getDefaultLocale();

        # Update cookiez
        setcookie('lang', $currentLang, time() + ( SiteConfig::COOKIEVALIDITY * 24 * 60 * 60), "/"); // 24 * 60 * 60 = 86400 = 1 day

        /*TODO REDIRECT TO ROUTE*/

        # some logic to determine the $locale
        //$request->setLocale($currentLang);
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
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}
