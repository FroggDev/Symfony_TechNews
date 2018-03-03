<?php

namespace App\Controller;

use App\Entity\Category;
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

    /**
     * @Route("/category/{label}/{currentPage}.{format}",
     *      name="index_category",
     *      methods={"GET"},
     *      defaults={"currentPage"="1", "format"=""})
     *
     * @param string $label
     * @param string $currentPage
     * @return Response
     */
    public function category(string $label, string $currentPage): Response
    {
        # get repo article
        $reposirotyCategory = $this->getDoctrine()->getRepository(Category::class);

        # get article from category
        $category = $reposirotyCategory->getArticleFromCategory($label);

        # check if category exist
        if (!$category) {
            return $this->redirectToRoute(
                'index',
                [],
                Response::HTTP_MOVED_PERMANENTLY
            );
        }

        # display page from twig template
        return $this->render('index/category.html.twig', [
            'articles' => $category->getArticles(),
            'category' => $category,
            'currentPage' => $currentPage
        ]);
    }
}
