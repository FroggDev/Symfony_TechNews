<?php
/**
 * Main page : IndexController
 * Date: 02/03/2018
 * Time: 22:21
 *
 * PHP Version 7.2
 *
 * @category Educational
 * @package  FroggDev/Symfony_TestProject
 * @author   Frogg <admin@frogg.fr>
 * @license  proprietary WebForce3
 * @link     https://github.com/FroggDev/Symfony_TestProject
 *
 * @Requirement for PHP
 * extension=php_fileinfo.dll for guessExtension()
 * extension=mysqli for Doctrine
 * extension=pdo_mysql for Doctrine
 * @TODO : check if doctrine use mysqli or pdo_mysql
 */

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # Get spotlights
        $spotlights = $repositoryArticle->findSpotLightArticles();

        # display page from twig template
        return $this->render('index/index.html.twig', [
            'spotlights' => $spotlights
        ]);
    }

    /**
     * @return Response
     * @TODO move this to somewhere else more meaningful
     */
    public function sideBar() : Response
    {
        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # display page from twig template
        return $this->render(
            'components/_sidebar_html.twig',
            [
                # get repo article
                'specialArticles' => $repositoryArticle->findSpecialArticles(),
                # Get last five Articles
                'lastFiveArticles' => $repositoryArticle->findLastFiveArticle()
            ]
        );
    }
}
