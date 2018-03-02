<?php
namespace App\Controller\TechNews;


use App\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * @Route("/author/{firstname}-{lastname}_{id}/{currentPage}",
     *      name="index_author",
     *      methods={"GET"},
     *      requirements={"firstname" : "[\w-]+"},
     *      requirements={"lastname" : "[\w-]+"},
     *      requirements={"id" : "[\d-]+"},
     *      defaults={"currentPage"="1"}
     *      )
     *
     * @param string $label
     * @param string $currentPage
     * @return Response
     */
    public function index(string $firstname, string $lastname , $id, string $currentPage ): Response
    {

        ############
        # CATEGORY #
        ############

        # get repo article
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);

        # get article from category
        $author=$reposirotyAuthor->getArticleFromAuthor($id);

        # check if category exist
        if (!$author){
            return $this->redirectToRoute('index',[],Response::HTTP_MOVED_PERMANENTLY);
        }

        //VarDumper::dump($articles);
        //exit();

        return $this->render('index/author.html.twig', [
            'articles'        => $author->getArticles(),
            'author'          => $author,
            'currentPage'      => $currentPage
        ]);
    }
}