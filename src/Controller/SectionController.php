<?php
namespace App\Controller;

use App\Common\Util\WordTag;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SectionController extends Controller
{
    /**
     * @return Response
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
     * @return Response
     */
    public function wordTag(): Response
    {
        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # get all articles order by date desc
        $articles = $repositoryArticle->findBy([], ['dateCreation' => 'DESC']);
        # it is possible to get only article < than a date
        //$articles = $repositoryArticle->findArticleFromLastMonths(1);

        $titleList = [];
        foreach ($articles as $article) {
            # get all articles titles
            $titleList[] = $article->getTitle();
        }

        # create word tags
        $wordTag=new WordTag();

        # display page from twig template
        return $this->render(
            'components/_worcloud_html.twig',
            [
                'wordCloud' => $wordTag->getWordTags($titleList)
            ]
        );
    }
}
