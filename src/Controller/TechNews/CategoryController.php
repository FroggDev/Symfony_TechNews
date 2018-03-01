<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 01/03/2018
 * Time: 12:05
 */

namespace App\Controller\TechNews;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class CategoryController extends Controller
{
    /**
     * @Route("/{label}",
     *      name="index_category",
     *      methods={"GET"},
     *      requirements={"label" : "\w+"}),
     *      defaults={"label" : "All"})
     *
     * @param string $label
     * @return Response
     */
    public function category(string $label): Response
    {
        ###########
        # ARTICLE #
        ###########

        # get repo article
        $reposirotyArticle = $this->getDoctrine()->getRepository(Article::class);

        # Get specialArticles
        $specialArticles = $reposirotyArticle->findSpecialArticles();

        # Get spotlights
        $spotlights = $reposirotyArticle->findSpotLightArticles();

        # Get spotlights
        $lastFiveArticles = $reposirotyArticle->findLastFiveArticle();

        ############
        # CATEGORY #
        ############

        # get repo article
        $reposirotyCategory = $this->getDoctrine()->getRepository(Category::class);

        # get article from category
        $articles=$reposirotyCategory->getArticleFromCategory($label)->getArticles();

        //VarDumper::dump($articles);
        //exit();


        return $this->render('index/category.html.twig', [
            'articles'          => $articles,
            'category'          => $label,
            'spotlights'        => $spotlights
        ]);
    }


}