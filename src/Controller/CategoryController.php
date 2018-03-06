<?php

namespace App\Controller;

use App\Common\Traits\String\SlugifyTrait;
use App\Entity\Category;
use App\SiteConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @TODO : @ Entity("label", expr="repository.findOneByLabel(label)")
 *
 * /!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\
 *
 * http://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/converters.html
 *
 *!\/!\/!\/!\/!\ paramconverters V5 /!\/!\/!\/!\/!\
 *composer require symfony/expression-language
# Automaticaly fecthing param converter
# https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-paramconverter
# manual :
# https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
 */
class CategoryController extends Controller
{

    use SlugifyTrait;

    /**
     * @Route(
     *      "/category/{label}/{currentPage}.html",
     *      name="index_category",
     *      methods={"GET"},
     *      requirements={"label" : "[a-z0-9-]+"},
     *      requirements={"currentPage" : "\d+"},
     *      defaults={"currentPage"="1"}
     * )
     *
     * @param string $label
     * @param string $currentPage
     * @return Response
     */
    public function category(string $label, string $currentPage): Response
    {
        $labelSlugified = $this->slugify($label);

        # get repo category
        $reposirotyCategory = $this->getDoctrine()->getRepository(Category::class);

        # get category from category
        $category = $reposirotyCategory->getCategoryFromName($labelSlugified);

        # check if category exist
        if (!$category) {
            return $this->redirectToRoute(
                'index',
                [],
                Response::HTTP_MOVED_PERMANENTLY
            );
        }

        # check url format else redirect to formated url
        if ($label != $category->getLabelSlugified()) {
            return $this->redirectToRoute(
                'index_category',
                [
                    'label' => $category->getLabelSlugified(),
                ],
                Response::HTTP_MOVED_PERMANENTLY
            );
        }

        #get Article array
        $matches = $category->getArticles()->getValues();

        # get number of elenmts
        $countArticle =count($matches);

        # get only wanted articles
        $articles = array_slice($matches, ($currentPage-1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination =  ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        # display page from twig template
        return $this->render('index/category.html.twig', [
            'articles' => $articles,
            'category' => $category,
            'currentPage' => $currentPage,
            'countPagination' => $countPagination
        ]);
    }
}
