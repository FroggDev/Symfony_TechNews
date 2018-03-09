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
/**
 * @see https://mailtrap.io
 * mailtrap@frogg.fr
 * testtest
 *
 * swiftmailer:
 * spool:     { type: memory }
 * transport: smtp
 *   host:      smtp.mailtrap.io
 *   username:  e5e05820e45013
 *   password:  e4ecbcfef4fb67
 *   auth_mode: cram-md5
 *   port: 2525
 */

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends Controller
{
    /**
     * @Route("/{_locale}")
     * @return Response
     */
    public function index(Request $request): Response
    {

        $locale = $request->getLocale();
        VarDumper::dump($locale);

        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # Get spotlights
        $spotlights = $repositoryArticle->findSpotLightArticles();

        # display page from twig template
        return $this->render('index/index.html.twig', [
            'spotlights' => $spotlights
        ]);
    }
}
