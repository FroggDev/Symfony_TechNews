<?php
/**
 * Created by PhpStorm.
 * User: Remy
 * Date: 01/03/2018
 * Time: 23:06
 */

namespace App\Service\Twig;


use App\Entity\Article;
use App\Entity\Author;

use App\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

class AppRuntime
{

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function maxLen($text, $size)
    {

        $string = strip_tags($text);

        if ($string > $size) {
            $stringCut = substr($string, 0, $size);
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')). "...";
        }

        return $string;
    }

    /*
    {{ article | articleLink(47) | raw }}
    {{ article | articleImageLink | raw }}
    {{ article | articleImage("img-responsive") | raw }}
    {{ article.author | authorLink | raw }}
    {{ article.category.label | categoryLink("cate-tag") | raw  }}
    */

    public function articleLink(Article $article, int $size = null)
    {
        $linkText = $size ? self::maxLen($article->getTitle(), $size) : $article->getTitle();

        return '<a href="' . $this->getArticleHref($article) . '">' . $linkText . '</a>';
    }

    public function articleImageLink(Article $article)
    {
        return '<a href="' . $this->getArticleHref($article) . '">' . $this->articleImage($article) . '</a>';
    }

    public function articleImage(Article $article, string $class='')
    {
        //$this->container->get('assets.packages')->getUrl('images/product/' . $article->getFeaturedImage())

        return '<img alt="'.$article->getTitle().'" src="/images/product/' . $article->getFeaturedImage().'" class="'.$class.'" />';
    }

    private function getArticleHref(Article $article)
    {
        return $this->router->generate('index_article',
            [
                'category' => $article->getCategory()->getLabelUrlified(),
                'slug' => $article->getSlugyfiedTitle(),
                'id' => $article->getId()
            ]);
    }

    public function categoryLink(Category $category, string $class = "")
    {
        $url = $this->router->generate('index_category', ['label' => $category->getLabelUrlified() ]);

        return '<a href="' . $url . '" class="' . $class . '">' . $category->getLabel() . '</a>';
    }

    public function categoryPaginationLink( Category $category, $text , $currentPage, string $class = "")
    {
        $url = $this->router->generate('index_category', ['label' => $category->getLabelUrlified() ,'currentPage' => $currentPage]);

        return '<a href="' . $url . '" class="' . $class . '">' . $text . '</a>';
    }

    public function authorLink(Author $author, string $class = "")
    {
        $url = $this->router->generate('index_author',
            [
                'firstname' => $author->getFirstName(),
                'lastname' => $author->getLastName(),
                'id' => $author->getId()
            ]);

        return '<a class="' . $class . '" href="' . $url . '">'
            . $author->getFirstName()
            . ' '
            . $author->getLastName()
            . '</a>';
    }
}