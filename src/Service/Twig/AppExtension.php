<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 01/03/2018
 * Time: 14:18
 */

namespace App\Service\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_Filter('maxLen', [AppRuntime::class, 'maxLen']),
            new \Twig_Filter('articleLink', [AppRuntime::class, 'articleLink']),
            new \Twig_Filter('articleImage', [AppRuntime::class, 'articleImage']),
            new \Twig_Filter('articleImageLink', [AppRuntime::class, 'articleImageLink']),
            new \Twig_Filter('categoryLink', [AppRuntime::class, 'categoryLink']),
            new \Twig_Filter('categoryPaginationLink', [AppRuntime::class, 'categoryPaginationLink']),
            new \Twig_Filter('authorLink', [AppRuntime::class, 'authorLink'])
        ];
    }
}