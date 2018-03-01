<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 26/02/2018
 * Time: 14:18
 */

namespace App\Controller\TechNews;

use App\Entity\Article;

use App\Service\Article\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @param ArticleProvider $articleProvider
     * @return Response
     */
    public function index(ArticleProvider $articleProvider): Response
    {
        # same as :
        # $this->container->get('article_provider');
        # like :
        # if( instance n extiste pas ) create new Instance ArticleProvider $articleProvider;
        # else return instanciated instance

        ###########
        # ARTICLE #
        ###########

        # get repo article
        $reposirotyArticle = $this->getDoctrine()->getRepository(Article::class);

        # Get specialArticles
        $specialArticles = $reposirotyArticle->findSpecialArticles();

        # Get spotlights
        $spotlights = $reposirotyArticle->findSpotLightArticles();

        //VarDumper::dump($lastFiveArticles);
        //exit();

        return $this->render('index/index.html.twig', [
            'spotlights' => $spotlights
        ]);

    }

    public function sideBar()
    {
        # get repo article
        $reposirotyArticle = $this->getDoctrine()->getRepository(Article::class);

        # Get specialArticles
        $specialArticles = $reposirotyArticle->findSpecialArticles();

        # Get specialArticles
        $lastFiveArticles = $reposirotyArticle->findLastFiveArticle();

        return $this->render('components/_sidebar_html.twig',
            [
                'specialArticles' => $specialArticles,
                'lastFiveArticles' => $lastFiveArticles
            ]);

    }

}