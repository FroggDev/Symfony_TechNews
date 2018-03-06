<?php

namespace App\Service\Twig\Entity;

use App\Common\Traits\Html\ATagGeneratorTrait;
use App\Entity\Category;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CategoryAppRuntime
 * @package App\Service\Twig\Entity
 */
class CategoryAppRuntime
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
     * @param Category $category
     * @param string|null $text
     * @param string|null $class
     * @param string|null $currentPage
     * @return string
     */
    public function categoryLink(
        Category $category,
        string $class = null,
        string $text = null,
        string $currentPage = null
    ): string {
        return $this->getATag(
            $this->getCategoryHref($category, $currentPage),
            $text ?? $category->getLabel(),
            $class
        );
    }

    /**
     * @param Category $category
     * @param string $currentPage
     * @return string
     */
    private function getCategoryHref(Category $category, string $currentPage = null): string
    {
        $routeParams = [
            'label' => $category->getLabelSlugified()
        ];

        if ($currentPage) {
            $routeParams['currentPage'] = $currentPage;
        }

        return $this->router->generate('index_category', $routeParams);
    }
}
