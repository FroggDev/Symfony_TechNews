<?php

namespace App\Controller;

use App\Common\Traits\String\SlugifyTrait;
use App\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorController
 * @package App\Controller
 */
class AuthorController extends Controller
{

    use SlugifyTrait;

    /**
     * @Route(
     *     "/author/{name}/{currentPage}.html",
     *      name="index_author",
     *      methods={"GET"},
     *      requirements={"name" : "[a-z0-9-]+"},
     *      requirements={"currentPage" : "\d+"},
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


        #get Article array
        $matches = $author->getArticles()->getValues();

        # get number of elenmts
        $countArticle =count($matches);

        # get only wanted articles
        $articles = array_slice( $matches ,($currentPage-1) * SiteConfig::NBARTICLEPERPAGE , SiteConfig::NBARTICLEPERPAGE );

        # number of pagination
        $countPagination =  ceil($countArticle / SiteConfig::NBARTICLEPERPAGE );

        # display page from twig template
        return $this->render('index/author.html.twig', [
            'articles' => $articles,
            'author' => $author,
            'currentPage' => $currentPage,
            'countPagination' => $countPagination
        ]);
    }
}
