<?php

namespace App\Service\Twig\Entity;

use App\Common\Util\Html\ATagGeneratorTrait;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

class SearchAppRuntime
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
     * @param string $search
     * @param string|null $class
     * @param string|null $text
     * @param string|null $currentPage
     * @param string $searchType
     * @return string
     */
    public function searchLink(
        string $search,
        string $class = null,
        string $text,
        string $currentPage = null,
        string $searchType
    ): string {
        return $this->getATag(
            $this->getSearchHref($search, $searchType, $currentPage),
            $text,
            $class
        );
    }


    /**
     * @param string $search
     * @param string $searchType
     * @param string|null $currentPage
     * @return string
     */
    private function getSearchHref(string $search, string $searchType, string $currentPage = null): string
    {
        $routeParams = [
            'search' => $search
        ];

        if ($currentPage) {
            $routeParams['currentPage'] = $currentPage;
        }

        return $this->router->generate($searchType."_search", $routeParams);
    }
}
