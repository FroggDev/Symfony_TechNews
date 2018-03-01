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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Article\ArticleProvider;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{

    /**
     * @Route("/{category}/{slug}_{id}.html",
     *      name="index_article",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"}),
     *      requirements={"category" : "^!author$"}),
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
        # check if article exist
        if (!$article){
            return $this->redirectToRoute('index',Response::HTTP_MOVED_PERMANENTLY);
        }

        $currentCategory    = $article->getCategory()->getLabel();
        $slugyfiedTitle     = $article->getSlugyfiedTitle();

        # check url format
        if ( $category != $article->getCategory()->getLabel()  ){ //|| $slug != $slugyfiedTitle
            $this->redirect("/$currentCategory/${slugyfiedTitle}_${id}");
        }

        # Get suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($id,$article->getCategory()->getId());

        # Get spotlights
        $spotlight = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findSpotLightArticles();

        #render display
        return $this->render('index/article.html.twig', [
            'article'       => $article,
            'suggestions'   => $suggestions,
            'spotlights'    => $spotlight
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
     * @Route("/insert_article.html",
     *      name="insert_article",
     *      methods={"GET"})
     * @return Response
     */
    public function create(): Response
    {
        # create a Category
        $category = new Category();
        $category->setLabel('Other');

        # create an Author
        $author = new Author();
        $author->setEmail('test1@frogg.fr')
            ->setFirstName('Frogg')
            ->setLastName('Froggiz')
            ->setPassword('This is a pass')
            ->setRoles(['Admin',['Contributor']]);

        # create an Article
        $article = new Article();
        $article->setTitle('Test Tile')
            ->setContent('<p>This is a content</p>')
            ->setCategory($category)
            ->setAuthor($author)
            ->setSpecial(true)
            ->setSpotlight(true)
            ->setFeaturedImage('1.jpg');

        # you can fetch the EntityManager via $this->getDoctrine()
        # or you can add an argument to your action: index(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($article);
        $em->persist($category);
        $em->persist($article);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->render('index/article.html.twig', [
            'article' => $article
        ]);

    }



}