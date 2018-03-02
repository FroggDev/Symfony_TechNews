<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/02/2018
 * Time: 15:06
 */

namespace App\Controller\TechNews;

use App\Entity\Article;
use App\Entity\Author;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{

    /**
     * TODO ADD /article after testing
     */
    /**
     * @Route("/article/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"},
     *      requirements={"category" : "^(?!author).*$"})
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
    public function article(Article $article, $category, $slug, $id): Response
    {
        # check if article exist
        if (!$article) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        $currentCategory = $article->getCategory()->getLabelUrlified();
        $slugyfiedTitle = $article->getSlugyfiedTitle();

        # check url format
        if ($category != $currentCategory || $slug != $slugyfiedTitle) {
            return $this->redirectToRoute(
                'index_article',
                [
                    'category' => $currentCategory,
                    'slug' => $slugyfiedTitle,
                    'id' => $id
                ],
                Response::HTTP_MOVED_PERMANENTLY);
        }

        # Get suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($id, $article->getCategory()->getId());

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
            'article' => $article,
            'suggestions' => $suggestions,
            'spotlights' => $spotlight
        ]);
    }

    /**
     * @route("/creer_un_article.html")
     * @param Request $request
     * @return Response
     */
    public function addArticle(Request $request) : Response
    {
        # init new article object
        $article = new Article();

        # get an author for the demo
        $author = $this
            ->getDoctrine()
            ->getRepository(Author::class)
            ->find(1);

        # set author to the article
        $article->setAuthor($author);

        # https://symfony.com/doc/current/reference/forms/types.html
        $form = $this->createForm(ArticleType::class , $article);

        # Form posted data management
        $form->handleRequest($request);

        # Check the form (order is important)
        if($form->isSubmitted() && $form->isValid()){

            # get datas
            $article = $form->getData();

            # get the image file
            $featuredImage = $article->getFeaturedImage();

            # get file name
            $fileName = $article->getSlugified() .'.'. $featuredImage->guessExtension();

            # move uploaded file
            $featuredImage->move(
                $this->getParameter('app.articles.assets.dir'),
                $fileName
            );

            # insert Into database
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            # redirect on the created article
            return $this->redirectToRoute(
                'index_article',
                [
                    'category' => $article->getCategorie()->getLabelUrlified(),
                    'slug' => $article->getSlugyfiedTitle(),
                    'id' => $article->getId()
                ]);
        }


        return $this->render('form/article/addArticle.html.twig', array(
            'form' => $form->createView(),
        ));


    }

}