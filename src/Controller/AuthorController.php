<?php

namespace App\Controller;

use App\Common\Util\String\SlugifyTrait;
use App\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{

    use SlugifyTrait;

    /**
     * @Route("/author/{name}/{currentPage}.html",
     *      name="index_author",
     *      methods={"GET"},
     *      requirements={"name" : "[a-z0-9-]+"},
     *      requirements={"currentPage" : "[\d-]+"},
     *      defaults={"currentPage" : 1}
     *      )
     *
     * @param string $name
     * @param string $currentPage
     * @return Response
     */
    public function index(string $name, string $currentPage): Response
    {
        $currentNameSlugified = $this->slugify($name);

        # get repo author
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);

        # get author from name
        $author = $reposirotyAuthor->getAuthorFromName($this->slugify($currentNameSlugified));

        # check if author exist
        if (!$author) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # check url format else redirect to formated url
        if ($name != $author->getNameSlugified()) {
            return $this->redirectToRoute(
                'index_author',
                [
                    'name' => $author->getNameSlugified(),
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
