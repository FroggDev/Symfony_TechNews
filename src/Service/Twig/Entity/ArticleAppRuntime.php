<?php

namespace App\Service\Twig\Entity;

use App\Common\Traits\Html\ATagGeneratorTrait;
use App\Common\Traits\Html\ImgTagGeneratorTrait;
use App\Common\Traits\String\MaxLengthTrait;
use App\Entity\Article;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\RouterInterface;

class ArticleAppRuntime
{

    use ATagGeneratorTrait;

    use MaxLengthTrait;

    use ImgTagGeneratorTrait;

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
     * @param Article $article
     * @param int|null $size
     * @return string
     */
    public function articleLink(Article $article, int $size = null) : string
    {
        $linkText = $size ? $this->maxLength($article->getTitle(), $size) : $article->getTitle();

        return $this->getATag($this->getArticleHref($article), $linkText);
    }


    /**
     * @param Article $article
     * @return string
     */
    public function articleImageLink(Article $article) : string
    {
        return $this->getATag($this->getArticleHref($article), $this->articleImage($article));
    }


    /**
     * @param Article $article
     * @param string|null $class
     * @return string
     */
    public function articleImage(Article $article, string $class = null) : string
    {
        return $this->getImgTag(
            $this->asset->getUrl('images/product/' . $article->getFeaturedImage()),
            $article->getTitle(),
            $class
        );
    }


    /**
     * @param Article $article
     * @return string
     */
    private function getArticleHref(Article $article) : string
    {
        return $this->router->generate(
            'index_article',
            [
                'category' => $article->getCategory()->getLabelSlugified(),
                'slug' => $article->getTitleSlugified(),
                'id' => $article->getId()
            ]
        );
    }
}
