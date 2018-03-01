<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 01/03/2018
 * Time: 14:18
 */

namespace App\Service\Twig;


use App\Entity\Article;
use App\Entity\Author;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    //private $router;

    public function __construct(/*$router*/)
    {
        //$this->$router = $router;
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('maxLen', [$this, 'maxLen']),
            new \Twig_Filter('slugify', [$this, 'slugify']),
            new \Twig_Filter('articleLink', [$this, 'articleLink']),
            new \Twig_Filter('categoryLink', [$this, 'categoryLink']),
            new \Twig_Filter('authorLink', [$this, 'authorLink'])
        ];
    }

    public function maxLen($text, $size)
    {

        $string = strip_tags($text);

        if ($string > $size) {
            $stringCut = substr($string, 0, $size);
            $string = substr($stringCut, 0, strrpos($stringCut, ' '));
        }

        return $string . "...";
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /*
    {{ article | articleLink(47) | raw }}
    {{ article.author | authorLink | raw }}
    {{ article.category.label | categoryLink("cate-tag") | raw  }}
    */

    public function articleLink(Article $article,int $size=null)
    {

        $linkText=$size? self::maxLen($article->getTitle(), $size) : $article->getTitle();

        /**
         * injecter le router dans le service
         * TODO use path('index_article', instead
         * TODO auto add raw at exit of function
         */
        /*
        $url = $this->$router->generate('index_article',
                [
                    $article->getCategory()->getLabel(),
                    $article->getSlugyfiedTitle(),
                    $article->getId()
                    ]);*/

        return '<a href="'
            . $article->getCategory()->getLabel()
            . '/'
            . $article->getSlugyfiedTitle()
            . '_'
            . $article->getId() . '.html">'
            . $linkText
            . '</a>';
    }

    public function categoryLink(string $category, string $class="")
    {
        /**
         * TODO use path('index_category', instead
         * TODO auto add raw at exit of function
         */
        return '<a href="' . $category . '" class="'.$class.'">' . $category . '</a>';
    }

    public function authorLink(Author $author, string $class="")
    {

        /**
         * TODO use path('index_author', instead
         * TODO auto add raw at exit of function
         */
        return '';
        /*
        return '<a href="/author/'
            . $author.getFirstName()
            . '-'
            . $author.getLastName()
            . '_'
            . $author->getId() . '">'
            . $author.getFirstName()
            . ' '
            . $author.getLastName()
            . '</a>';*/
    }

}