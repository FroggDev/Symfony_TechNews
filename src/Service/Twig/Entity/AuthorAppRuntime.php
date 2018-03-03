<?php

namespace App\Service\Twig\Entity;

use App\Common\Util\Html\ATagGeneratorTrait;
use App\Entity\Author;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

class AuthorAppRuntime
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
     * @param Author $author
     * @param string|null $class
     * @param string|null $text
     * @param string|null $currentPage
     * @return string
     */
    public function authorLink(
        Author $author,
        string $class = null,
        string $text = null,
        string $currentPage = null
    ): string {
        return $this->getATag(
            $this->getAuthorHref($author, $currentPage),
            $text ?? $author->getFirstName() . ' ' . $author->getLastName(),
            $class
        );
    }


    /**
     * @param Author $author
     * @param string|null $currentPage |null
     * @return string
     */
    private function getAuthorHref(Author $author, string $currentPage = null): string
    {

        $routeParams = [
            'name' => $author->getNameSlugified()
        ];

        if ($currentPage) {
            $routeParams['currentPage'] = $currentPage;
        }

        return $this->router->generate('index_author', $routeParams);
    }
}
