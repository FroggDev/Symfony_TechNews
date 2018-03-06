<?php

namespace App\Service\Twig;

use App\Service\Twig\Common\StringAppRuntime;
use App\Service\Twig\Entity\ArticleAppRuntime;
use App\Service\Twig\Entity\AuthorAppRuntime;
use App\Service\Twig\Entity\CategoryAppRuntime;
use App\Service\Twig\Entity\LastAppRuntime;
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
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('maxLen', [StringAppRuntime::class, 'maxLength']),
        ];
    }

    /**
     * @return array
     *
     * {{ articleLink(article,47) | raw }}                          //maxlen size is optional
     * {{ articleImageLink(article) | raw }}                        //css is optional
     * {{ articleImage(article,"img-responsive") | raw }}           //css is optional
     * {{ authorLink(article.author) | raw }}                       //css is optional
     * {{ categoryLink(article.category.label,"cate-tag") | raw  }} //css is optional
     * {{ searchLink(search,"cate-tag") | raw  }}                   //css is optional
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('getUri', [AppRuntime::class, 'getUri']),
            new \Twig_Function('isNewsletterModal', [AppRuntime::class, 'isNewsletterModal']),
            # Links
            new \Twig_Function('articleLink', [ArticleAppRuntime::class, 'articleLink']),
            new \Twig_Function('articleImage', [ArticleAppRuntime::class, 'articleImage']),
            new \Twig_Function('articleImageLink', [ArticleAppRuntime::class, 'articleImageLink']),
            new \Twig_Function('categoryLink', [CategoryAppRuntime::class, 'categoryLink']),
            new \Twig_Function('authorLink', [AuthorAppRuntime::class, 'authorLink']),
            new \Twig_Function('searchLink', [SearchAppRuntime::class, 'searchLink']),
            new \Twig_Function('lastLink', [LastAppRuntime::class, 'lastLink'])
        ];
    }
}
