<?php
namespace App\Controller;

use App\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class AuthorController extends Controller
{
    /**
     * @Route("/author/{firstname}_{lastname}_{id}/{currentPage}.{format}",
     *      name="index_author",
     *      methods={"GET"},
     *      requirements={"firstname" : "[\w-]+"},
     *      requirements={"lastname" : "[\w-]+"},
     *      requirements={"id" : "[\d-]+"},
     *      defaults={"currentPage"="1", "format"=""})
     *      )
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $currentPage
     * @return Response
     */
    public function index(string $firstname, string $lastname, $id, string $currentPage): Response
    {
        # get repo article
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);

        # get article from category
        $author = $reposirotyAuthor->getArticleFromAuthor($id);

        # check if category exist
        if (!$author) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # check url format else redirect to formated url
        if ($firstname != $author->getFirstNameSlugified() || $lastname != $author->getLastNameSlugified()) {
            return $this->redirectToRoute(
                'index_author',
                [
                    'firstname' => $author->getFirstNameSlugified(),
                    'lastname' => $author->getLastNameSlugified(),
                    'id' => $id
                ],
                Response::HTTP_MOVED_PERMANENTLY
            );
        }

        # display page from twig template
        return $this->render('index/author.html.twig', [
            'articles' => $author->getArticles(),
            'author' => $author,
            'currentPage' => $currentPage
        ]);
    }
}
