<?php
namespace App\Service\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AppRuntime
 * @package App\Service\Twig
 */
class AppRuntime
{
    private $requestStack;

    private $session;

    /**
     * AppRuntime constructor.
     * @param RequestStack $requestStack
     * @param SessionInterface $session
     */
    public function __construct(RequestStack $requestStack, SessionInterface $session)
    {
        $this->requestStack=$requestStack;
        $this->session=$session;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $stack = $this->requestStack->getMasterRequest();
        return $stack->getRequestUri();
    }

    /**
     * @return bool
     */
    public function isNewsletterModal() : ?bool
    {
        return $this->session->get('newsletterModal');
    }
}
