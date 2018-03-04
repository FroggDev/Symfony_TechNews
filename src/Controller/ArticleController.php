<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends Controller
{
    /**
     * @Route(
     *      "/article/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"}
     *     )
     *
     * @param Article $article
     * @param string $category
     * @param string $slug
     * @param string $id
     * @return Response
     */
    public function article(Article $article, string $category, string $slug, string $id): Response
    {
        # check if article exist
        if (!$article) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # get slugified datas
        $categorySlugified = $article->getCategory()->getLabelSlugified();
        $titleSlugified = $article->getTitleSlugified();

        # check url format
        if ($category != $categorySlugified || $slug != $titleSlugified) {
            return $this->redirectToRoute(
                'index_article',
                [
                    'category' => $categorySlugified,
                    'slug' => $titleSlugified,
                    'id' => $id
                ],
                Response::HTTP_MOVED_PERMANENTLY
            );
        }

        # Get suggestions
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($article->getCategory()->getId());

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
        # $category = $article->getCategory()->getLabel();
        # VarDumper::dump($category);
        # exit();

        # display page from twig template
        return $this->render('index/article.html.twig', [
            'article' => $article,
            'suggestions' => $suggestions,
            'spotlights' => $spotlight
        ]);
    }

    /**
     * @route(
     *     "/addArticle.html",
     *     name="add_article"
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function addArticle(Request $request): Response
    {
        # init new article object
        $article = new Article();

        # get an author for the demo
        $author = $this
            ->getDoctrine()
            ->getRepository(Author::class)
            ->find(3);

        # set author to the article
        $article->setAuthor($author);

        # https://symfony.com/doc/current/reference/forms/types.html
        $form = $this->createForm(ArticleType::class, $article);

        # Form posted data management
        $form->handleRequest($request);

        # Check the form (order is important)
        if ($form->isSubmitted() && $form->isValid()) {
            # get datas
            $article = $form->getData();

            # get the image file
            $featuredImage = $article->getFeaturedImage();

            # Only if image exist
            if ($featuredImage) {
                # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                # get file name DO NOT FORGET TO ENABLE extension=php_fileinfo.dll
                # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                $fileName = $article->getTitleSlugified() . '.' . $featuredImage->guessExtension();

                # move uploaded file
                $featuredImage->move(
                    $this->getParameter('app.articles.assets.dir'),
                    $fileName
                );

                # update image name
                $article->setFeaturedImage($fileName);
            }

            #set slugified title
            $article->setManualyTitleSlugified();

            # insert Into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($article);
            $eManager->flush();

            # redirect on the created article
            return $this->redirectToRoute(
                'index_article',
                [
                    'category' => $article->getCategory()->getLabelSlugified(),
                    'slug' => $article->getTitleSlugified(),
                    'id' => $article->getId()
                ]
            );
        }

        return $this->render('form/article/addArticle.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @route(
     *     "/modArticle/{slug}_{id}.html",
     *     name="mod_article"
     * )
     *
     * @param Article $article
     * @param Request $request
     * @param string $slug
     * @param string $id
     * @return Response
     */
    public function modArticle(Article $article, Request $request, string $slug, string $id): Response
    {
        # check if article exist
        if (!$article) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # check article author
        if ($article->getAuthor()->getId()!=3) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        $defaultImage = $article->getFeaturedImage();

        # https://symfony.com/doc/current/reference/forms/types.html
        $form = $this->createForm(ArticleType::class, $article);

        # Form posted data management
        $form->handleRequest($request);

        # Check the form (order is important)
        if ($form->isSubmitted() && $form->isValid()) {
            # get datas
            $article = $form->getData();

            # get the image file
            $featuredImage = $article->getFeaturedImage();

            # Only if image exist
            if ($featuredImage) {
                # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                # get file name DO NOT FORGET TO ENABLE extension=php_fileinfo.dll
                # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                $fileName = $article->getTitleSlugified() . '.' . $featuredImage->guessExtension();

                # move uploaded file
                $featuredImage->move(
                    $this->getParameter('app.articles.assets.dir'),
                    $fileName
                );

                # update image name
                $article->setFeaturedImage($fileName);
            } else {
                # restore original image if not modified
                $article->setFeaturedImage($defaultImage);
            }

            #set slugified title
            $article->setManualyTitleSlugified();

            # insert Into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($article);
            $eManager->flush();

            # redirect on the created article
            return $this->redirectToRoute(
                'index_article',
                [
                    'category' => $article->getCategory()->getLabelSlugified(),
                    'slug' => $article->getTitleSlugified(),
                    'id' => $article->getId()
                ]
            );
        }

        return $this->render('form/article/addArticle.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
