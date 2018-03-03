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
    public function sideBar(): Response
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

    /**
     * @TODO move this to somewhere else more meaningful
     * @return Response
     */
    public function wordTag(): Response
    {
        $excludedWords = [
            'one','two','three','four','five','six','seven','height','nine','ten',
            'million','thousand','hundred',
            'yes','no',
            'a','e','i','o','u','y',
            'you','he','she','we','they',
            'my','mine','your','yours','him','her','hers','our','them',
            'with',
            'monday','tuesday','wensday','thursday','friday','saturday','sunday',
            'now','tomorow',
            'to','from','for','in','on','of','off',
            'a','an','the',
            'be','is','will',
            'and','so',
            'going','being',
            'little','big',
            'change','ways','tip','says'
        ];

        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # get all articles order by date desc
        $articles = $repositoryArticle->findBy([], ['dateCreation' => 'DESC']);

        # it is possible to get only article < than a date
        //$articles = $repositoryArticle->findArticleFromLastMonths(1);

        # collect words & weight
        $wordCollector = [];

        foreach ($articles as $article) {
            # get all words from titles
            $articleWords = explode(" ", $article->getTitle());

            foreach ($articleWords as $word) {
                #set words to lowercase
                $word = strtolower($word);
                # skip numeric words
                if (is_numeric($word) || in_array($word, $excludedWords)) {
                    continue;
                }
                # add to word list or increment
                isset($wordCollector[$word]) ?
                    $wordCollector[$word]++ :
                    $wordCollector[$word] = 0;
            }
        }

       #order by weight
        arsort($wordCollector);

        # 10 first result
        $wordCollector = array_slice($wordCollector, 0, 10);

        # display page from twig template
        return $this->render(
            'components/_worcloud_html.twig',
            [
                'wordCloud' => $wordCollector,
            ]
        );
    }
}
