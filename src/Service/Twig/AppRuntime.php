<?php
namespace App\Service\Twig;

use Symfony\Component\HttpFoundation\RequestStack;


class AppRuntime
{
    private $requestStack;

    /**
     * AppRuntime constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack=$requestStack;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $stack = $this->requestStack->getMasterRequest();
        return $stack->getRequestUri();
    }
}