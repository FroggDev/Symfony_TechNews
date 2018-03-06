<?php

namespace App\Service\Twig\Entity;

use App\Common\Traits\Html\ATagGeneratorTrait;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class LastAppRuntime
 * @package App\Service\Twig\Entity
 */
class LastAppRuntime
{

    use ATagGeneratorTrait;

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Packages
     */
    public $asset;


    /**
     * AppRuntime constructor.
     * @param RouterInterface $router
     * @param Packages $asset
     *
     * @author Frogg <admin@frogg.fr>
     * @contributor  Sandy Pierre <sandy.pierre97@gmail.com>
     * Who found how to call the assets
     */
    public function __construct(RouterInterface $router, Packages $asset)
    {
        $this->router = $router;
        $this->asset = $asset;
    }


    /**
     * @param string $text
     * @param string|null $class
     * @param string|null $currentPage
     * @return string
     */
    public function lastLink(
        string $text,
        string $class = null,
        string $currentPage = null
    ): string {
        return $this->getATag(
            $this->getSearchHref($currentPage),
            $text,
            $class
        );
    }


    /**
     * @param string|null $currentPage
     * @return string
     */
    private function getSearchHref(string $currentPage = null): string
    {
        return $this->router->generate('last_article', ['currentPage' => $currentPage]);
    }
}
