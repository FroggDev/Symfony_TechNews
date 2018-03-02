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
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{

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
     */
    public function addArticle()
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
        $form = $this
            ->createFormBuilder($article)
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                    'placeholder' => 'Article title...',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'label',
                    'multiple' => false,
                    'expanded' => false,
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )->add(
                'content',
                TextareaType::class,
                [
                    'required' => true,
                    'label' => false,
                    'placeholder' => 'Article content...',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )->add(
                'featured_image',
                FileType::class,
                [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'class' => 'dropify'
                    ]
                ]
            )->add(
                'special',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => false,
                ]
            )->add(
                'spotlight',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => false,
                ]
            )->add(
                'submit', SubmitType::class,
                [
                    'label' => 'Publish',
                    'attr' => array('class' => 'btn btn-primary')
                ]
            )->getForm();

        return $this->render('form/article/addArticle.html.twig', array(
            'form' => $form->createView(),
        ));


    }

}