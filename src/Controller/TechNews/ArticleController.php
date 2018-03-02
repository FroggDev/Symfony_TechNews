<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/02/2018
 * Time: 15:06
 */

namespace App\Controller\TechNews;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{

    /**
     * @Route("/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"},
     *      requirements={"category" : "^(?!author$).*$"})
     *
     * @param Article $article
     * @param $category
     * @param $slug
     * @param $id
     * @return Response
     */
    # Automaticaly fecthing param converter
    # https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-paramconverter
    # manual :
    # https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
    public function article(Article $article,$category,$slug,$id): Response
    {
        # check if article exist
        if (!$article){
            return $this->redirectToRoute('index',[],Response::HTTP_MOVED_PERMANENTLY);
        }

        $currentCategory    = $article->getCategory()->getLabelUrlified();
        $slugyfiedTitle     = $article->getSlugyfiedTitle();

        # check url format
        if ( $category != $currentCategory || $slug != $slugyfiedTitle ){
            return $this->redirectToRoute(
                'index_article',
                    [
                    'category'=>$currentCategory ,
                     'slug' =>  $slugyfiedTitle,
                    'id'  =>  $id
                    ],
                Response::HTTP_MOVED_PERMANENTLY);
        }

        # Get suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($id,$article->getCategory()->getId());

        # Get spotlights
        $spotlight = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findSpotLightArticles();

        /*
         * Lazy Loading et le Chargement des Related Objects
         * Il est important de comprendre que nous avons accès à l'objet catégorie
         * de l'article de façon AUTOMATIQUE ! Cependant, les données de la catégorie
         * ne sont récupérés par doctrine que lorsque nous faisons la demande, et pas avant !
         * Ceci pour alléger le chargement de votre page !
         */
        # $categorie = $article->getCategory()->getLabel();
        # VarDumper::dump($categorie);
        # exit();

        #render display
        return $this->render('index/article.html.twig', [
            'article'       => $article,
            'suggestions'   => $suggestions,
            'spotlights'    => $spotlight
        ]);
    }
}