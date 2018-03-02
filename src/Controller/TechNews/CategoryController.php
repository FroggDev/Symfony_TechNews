<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 01/03/2018
 * Time: 12:05
 */

namespace App\Controller\TechNews;

use App\Common\Util\SpaceModifierTrait;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    use SpaceModifierTrait;

    /**
     * @Route("/{label}/{currentPage}.{format}",
     *      name="index_category",
     *      methods={"GET"},
     *      requirements={"label" : "[\w-]+"},
     *      defaults={"currentPage"="1", "format"="html"})
     *
     * @param string $label
     * @param string $currentPage
     * @return Response
     */
    public function category(string $label, string $currentPage): Response
    {

        $label = $this->traitToSpace($label);

        ############
        # CATEGORY #
        ############

        # get repo article
        $reposirotyCategory = $this->getDoctrine()->getRepository(Category::class);

        # get article from category
        $category=$reposirotyCategory->getArticleFromCategory($label);

        # check if category exist
        if (!$category){
            return $this->redirectToRoute('index',[],Response::HTTP_MOVED_PERMANENTLY);
        }

        //VarDumper::dump($articles);
        //exit();

        return $this->render('index/category.html.twig', [
            'articles'          => $category->getArticles(),
            'category'          => $category,
            'currentPage'       => $currentPage
        ]);
    }
}