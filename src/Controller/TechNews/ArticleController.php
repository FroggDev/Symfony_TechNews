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
     * @Route("/insert_article.html",
     *      name="insert_article",
     *      methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        # create a Category
        $category = new Category();
        $category->setLabel('Technology');

        # create an Author
        $author = new Author();
        $author->setEmail('test@frogg.fr')
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