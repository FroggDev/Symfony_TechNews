<?php

namespace App\Controller;

use App\Common\Util\WordTag;
use App\Entity\Article;
use App\Service\Article\ArticleCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class SectionController
 * @package App\Controller
 */
class SectionController extends Controller
{
    /**
     * @param ArticleCatalog $catalog
     * @return Response
     */
    public function sideBar(ArticleCatalog $catalog): Response
    {
        # get repo article (old, only from doctrine)
        /*
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);
        $repositoryArticle->findSpecialArticles()
        $repositoryArticle->findLastFiveArticle()
        */
        
        # display page from twig template
        return $this->render(
            'components/_sidebar_html.twig',
            [
                # get repo article
                'specialArticles' => $catalog->findSpecials(),
                # Get last five Articles
                'lastFiveArticles' => $catalog->findLastFive()
            ]
        );
    }

    /**
     * @return Response
     */
    public function wordTag(ArticleCatalog $catalog): Response
    {
        # FROM DOCTRINE
        /*
        # get repo article
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);

        # get all articles order by date desc
        $articles = $repositoryArticle->findBy([], ['dateCreation' => 'DESC']);
        # it is possible to get only article < than a date
        //$articles = $repositoryArticle->findArticleFromLastMonths(1);
        */

        # FROM CATALOG
        $articles = $catalog->findAll()->toArray();

        $titleList = [];
        foreach ($articles as $article) {
            # get all articles titles
            $titleList[] = $article->getTitle();
        }

        # create word tags
        $wordTag = new WordTag();

        # display page from twig template
        return $this->render(
            'components/_worcloud_html.twig',
            [
                'wordCloud' => $wordTag->getWordTags($titleList)
            ]
        );
    }
}
