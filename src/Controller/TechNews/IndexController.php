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
use Symfony\Component\Routing\Annotation\Route;

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

        # get Articles from ArticlesProvider
        $articles = $articleProvider->getArticles();
        return $this->render('index/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/category/{label}",
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
        return $this->render('index/category.html.twig', [
            'label' => $label
        ]);
    }

    /**
     * @Route("/long/{category}/{slug}_{id}.html",
     *      name="index_article_long",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"}),
     *      defaults={"label" : "All"})
     *
     * @param string $category
     * @param string $slug
     * @param string $id
     * @return Response
     * @TODO ADD SLUG getSlugyfiedTitle
     */
    public function articleLong(string $category, string $slug, string $id): Response
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (!$article){
            //throw $this->createNotFoundException("Article not found ${slug}_${id}");
            return $this->redirectToRoute('index',Response::HTTP_MOVED_PERMANENTLY);
        }

        $currentCategory    = $article->getCategory()->getLabel();
        $slugyfiedTitle     = $article->getSlugyfiedTitle();

        if ( $category != $article->getCategory()->getLabel()  ){ //|| $slug != $slugyfiedTitle
            $this->redirect("/$currentCategory/${slugyfiedTitle}_${id}");
        }

        /**
         * Lazy Loading et le Chargement des Related Objects
         * Il est important de comprendre que nous avons accès à l'objet catégorie
         * de l'article de façon AUTOMATIQUE ! Cependant, les données de la catégorie
         * ne sont récupérés par doctrine que lorsque nous faisons la demande, et pas avant !
         * Ceci pour alléger le chargement de votre page !
         */
        # $categorie = $article->getCategorie()->getLibelle();

        return $this->render('index/article.html.twig', [
            'article' => $article
        ]);

    }

    /**
     * @Route("/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"}),
     *      defaults={"label" : "All"})
     *
     * @param Article $article
     * @param $category
     * @param $slug
     * @param $id
     * @return Response
     * @TODO ADD SLUG getSlugyfiedTitle
     */
    # Automaticaly fecthing param converter
    # https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-paramconverter
    # manual :
    # https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
    public function article(Article $article,$category,$slug,$id): Response
    {
        if (!$article){
            return $this->redirectToRoute('index',Response::HTTP_MOVED_PERMANENTLY);
        }

        $currentCategory    = $article->getCategory()->getLabel();
        $slugyfiedTitle     = $article->getSlugyfiedTitle();

        if ( $category != $article->getCategory()->getLabel()  ){ //|| $slug != $slugyfiedTitle
            $this->redirect("/$currentCategory/${slugyfiedTitle}_${id}");
        }

        return $this->render('index/article.html.twig', [
            'article' => $article
        ]);
    }
}