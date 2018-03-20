<?php
namespace App\Controller;

use App\Common\Traits\Database\DatabaseTrait;
use App\Entity\Article;
use App\Entity\Author;
use App\Exception\DuplicateCatalogDataException;
use App\Form\ArticleType;
use App\Service\Article\ArticleCatalog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends Controller
{

    use DatabaseTrait;

    /**
     * @Route(
     *      "/{_locale}/article/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"}
     *     )
     *
     * //
     * @param string $category
     * @param string $slug
     * @param string $id
     * @param ArticleCatalog $catalog
     * @return Response
     */
    public function article(string $category, string $slug, string $id, ArticleCatalog $catalog): Response //Article $article
    {

        #ArticleCatalog $catalog
        #VarDumper::dump($catalog->getSources());
        #VarDumper::dump($catalog->findAll());
        #VarDumper::dump($catalog->findLastFive());
        #VarDumper::dump($catalog->findSpecials());
        #VarDumper::dump($catalog->findSpotlights());


        # get article from catalog and check if there is duplicate article
        try {
            $article = $catalog->find($id);
        } catch (DuplicateCatalogDataException $e) {
            # if duplicate get first article found
            $article = $e->getArticle();
        }

        # check if article exist
        if (!$article) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # get slugified datas
        $categorySlugified = $article->getCategory()->getLabelSlugified();
        $titleSlugified = $article->getTitleSlugified();

        # check url format, else redirect to formated route
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

        /**
         * DOCTRINE VERION
         */

        # Get suggestions
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        /*
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($article->getCategory()->getId());
        */

        # Get spotlights
        /*
        $spotlight = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findSpotLightArticles();
        */

        /**
         * CATALOG VERION
         */
        # Get spotlights from catalog
        $spotlight = $catalog->findSpotlights();
        # get suggestion
        $suggestions = $catalog->findSuggestions($article->getCategory()->getId());

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
     *     "/{_locale}/edition/editArticle/{id}.html",
     *     name="edit_article",
     *     defaults={"id"="0"}
     * )
     *
     * @param Request $request
     * @param string $id
     * @param Registry $workflows
     * @param Packages $asset
     * @return Response
     *
     * @see security.yaml @Security("has_role('ROLE_AUTHOR')")
     */
    public function editArticle(Request $request, string $id, Registry $workflows,Packages $asset): Response
    {
        # check if create or mod
        $isNewArticle = true;

        //check if box exist
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);

        # get an author from user logged in
        $author = $this
            ->getDoctrine()
            ->getRepository(Author::class)
            ->find($this->getUser()->getId());

        if (!$article) {
            $article = new Article();
            $article->setAuthor($author);
        } else {
            $isNewArticle = false;
            # check article author
            if ($article->getAuthor()->getId() != $this->getUser()->getId() && !$this->getUser()->hasRole("ROLE_ADMIN")) {
                # not allowed edition
                $this->addFlash('error', 'You can\'t edit this article');
                return $this->redirectToRoute('index_article', ['id' => $id,'slug' =>$article->getTitleSlugified()]);
            }
        }
        # get default image to restore it back if not modified
        $defaultImage = $article->getFeaturedImage();

        # get workflow
        $workflow = $workflows->get($article);

        # https://symfony.com/doc/current/reference/forms/types.html
        $form = $this->createForm(ArticleType::class, $article,['image_url' => $asset->getUrl('images/product/' . $defaultImage) ]);

        # Form posted data management
        $form->handleRequest($request);

        # Check the form (order is important)
        if ($form->isSubmitted() && $form->isValid()) {
            # get datas
            $article = $form->getData();

            # working with the workflow
            #==========================
            # a:1:{s:7:"publish";i:1;}
            # a:1:{s:6:"review";i:1;}
            if ($isNewArticle) {
                try {
                    $workflow->apply($article, 'to_review');
                } catch (LogicException $exception) {
                    # not allowed transition
                    $this->addFlash('error', 'An error occured in the workflow transition' . $exception->getMessage());
                    # redirect on the created article
                    return $this->redirectToRoute('edit_article', ['id' => $id]);
                };
            }
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
            $this->save($article);

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

        return $this->render('article/addArticle.html.twig', array(
            'form' => $form->createView(),
        ));
    }



    /**
     * Display author articles
     * @route(
     *     "/{_locale}/admin/delete-article/{id}.html",
     *     name="delete_article"
     * )
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Article $article
     */
    public function deleteArticle(Article $article)
    {
        /*
         * @TODO : manage this
         */
    }

}
