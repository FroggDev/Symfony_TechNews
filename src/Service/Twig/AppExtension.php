<?php

namespace App\Service\Twig;

use App\Service\Twig\Common\StringAppRuntime;
use App\Service\Twig\Entity\ArticleAppRuntime;
use App\Service\Twig\Entity\AuthorAppRuntime;
use App\Service\Twig\Entity\CategoryAppRuntime;
use App\Service\Twig\Entity\SearchAppRuntime;

/**
 * Class AppExtension
 * @package App\Service\Twig
 *
 * Custom twig filter
 * @url https://symfony.com/doc/current/templating/twig_extension.html
 */
class AppExtension extends \Twig_Extension
{

    /**
     * Twig calls :
     * {{ string  | maxLen(47) }}
     * {{ article | articleLink(47) | raw }}                         //maxlen size is optional
     * {{ article | articleImageLink | raw }}                        //css is optional
     * {{ article | articleImage("img-responsive") | raw }}          //css is optional
     * {{ article.author | authorLink | raw }}                       //css is optional
     * {{ article.category.label | categoryLink("cate-tag") | raw  }}//css is optional
     * {{ search | searchLink("cate-tag") | raw  }}//css is optional
     *
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('maxLen', [StringAppRuntime::class, 'maxLength']),
            new \Twig_Filter('articleLink', [ArticleAppRuntime::class, 'articleLink']),
            new \Twig_Filter('articleImage', [ArticleAppRuntime::class, 'articleImage']),
            new \Twig_Filter('articleImageLink', [ArticleAppRuntime::class, 'articleImageLink']),
            new \Twig_Filter('categoryLink', [CategoryAppRuntime::class, 'categoryLink']),
            new \Twig_Filter('authorLink', [AuthorAppRuntime::class, 'authorLink']),
            new \Twig_Filter('searchLink', [SearchAppRuntime::class, 'searchLink'])
        ];
    }
}
