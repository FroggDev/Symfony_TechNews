<?php
namespace App\Controller;
use App\Common\Traits\Database\DatabaseTrait;
use App\Entity\Article;
use App\SiteConfig;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;


/**
 * Class WorkflowController
 * @package App\Controller
 */
class WorkflowController extends Controller
{

    use DatabaseTrait;

    /**
     * Display author articles
     * @Route(
     *     "/{_locale}/edition/my-articles/{currentPage}.html",
     *     name="author_articles_published",
     *     defaults={"currentPage" : 1}
     * )
     * @security("has_role('ROLE_AUTHOR')")
     *
     * @param string $currentPage
     * @return Response
     */
    public function authorArticles(string $currentPage)
    {
        #get author
        $author = $this->getUser();

        #get artciles
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findAuthorArticlesByStatus($author->getId(), 'published');

        # get number of elenmts
        $countArticle = count($articles);

        # get only wanted articles
        $articles = array_slice($articles, ($currentPage - 1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination = ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        #display
        return $this->render('index/author.html.twig', array(
            'articles' => $articles,
            'author' => $author,
            'currentPage' => 1,
            'countPagination' => $countPagination
        ));

    }

    /**
     * Display author articles
     * @Route(
     *     "/{_locale}/edition/my-pending-articles/{currentPage}.html",
     *     name="author_articles_pending",
     *     defaults={"currentPage" : 1}
     * )
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @param string $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authorArticlesPending(string $currentPage)
    {
        #get author
        $author = $this->getUser();

        #get artciles
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findAuthorArticlesByStatus($author->getId(), 'review');


        # get number of elenmts
        $countArticle = count($articles);

        # get only wanted articles
        $articles = array_slice($articles, ($currentPage - 1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination = ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        #display
        return $this->render('index/author.html.twig', array(
            'articles' => $articles,
            'author' => $author,
            'currentPage' => 1,
            'countPagination' => $countPagination
        ));
    }

    /**
     * Display author articles
     * @Route(
     *     "/{_locale}/edition/my-approval-articles/{currentPage}.html",
     *     name="author_articles_approval",
     *     defaults={"currentPage" : 1}
     * )
     * @Security("has_role('ROLE_EDITOR')")
     *
     * @param string $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesApproval(string $currentPage)
    {
        #get author
        $author = $this->getUser();

        #get artciles
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findArticlesByStatus('editor');

        # get number of elenmts
        $countArticle = count($articles);

        # get only wanted articles
        $articles = array_slice($articles, ($currentPage - 1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination = ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        #display
        return $this->render('index/author.html.twig', array(
            'articles' => $articles,
            'author' => $author,
            'currentPage' => 1,
            'countPagination' => $countPagination
        ));
    }

    /**
     * Display author articles
     * @Route(
     *     "/{_locale}/edition/my-corrector-articles/{currentPage}.html",
     *     name="author_articles_corrector",
     *     defaults={"currentPage" : 1}
     * )
     * @Security("has_role('ROLE_CORRECTOR')")
     *
     * @param string $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesCorrector(string $currentPage)
    {
        #get author
        $author = $this->getUser();

        #get artciles
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findArticlesByStatus('corrector');

        # get number of elenmts
        $countArticle = count($articles);

        # get only wanted articles
        $articles = array_slice($articles, ($currentPage - 1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination = ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        #display
        return $this->render('index/author.html.twig', array(
            'articles' => $articles,
            'author' => $author,
            'currentPage' => 1,
            'countPagination' => $countPagination
        ));
    }

    /**
     * Display author articles
     * @Route(
     *     "/{_locale}/edition/my-publisher-articles/{currentPage}.html",
     *     name="author_articles_publisher",
     *     defaults={"currentPage" : 1}
     * )
     * @Security("has_role('ROLE_PUBLISHER')")
     *
     * @param string $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesPublisher(string $currentPage)
    {
        #get author
        $author = $this->getUser();

        #get artciles
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findArticlesByStatus('publisher');

        # get number of elenmts
        $countArticle = count($articles);

        # get only wanted articles
        $articles = array_slice($articles, ($currentPage - 1) * SiteConfig::NBARTICLEPERPAGE, SiteConfig::NBARTICLEPERPAGE);

        # number of pagination
        $countPagination = ceil($countArticle / SiteConfig::NBARTICLEPERPAGE);

        #display
        return $this->render('index/author.html.twig', array(
            'articles' => $articles,
            'author' => $author,
            'currentPage' => 1,
            'countPagination' => $countPagination
        ));
    }


    /**
     * @Route(
     *     "/{_locale}/edition/workflow/{action}/{id}.html",
     *      name="workflow_action"
     * )
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @param string $action
     * @param String $id
     * @param Registry $workflows
     * @param Request $request
     * @return Response
     */
    public function workflowAction(string $action, String $id, Registry $workflows, Request $request): Response
    {
        # check if box exist
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);

        if (!$article) {
            # not allowed transition
            $this->addFlash('error', 'Article not found');
        }

        $workflow = $workflows->get($article);

        # Do the action
        # -------------
        if ($workflow->can($article, $action)) {
            try {
                # apply transition
                $workflow->apply($article, $action);

                # insert Into database
                $this->save($article);

                # not allowed transition
                $this->addFlash('success', 'Article has been sent to the editor !');

            } catch (LogicException $exception) {
                # not allowed transition
                $this->addFlash('error', 'An error occured in the workflow transition' . $exception->getMessage());
            }
        } else {
            # not allowed transition
            $this->addFlash('error', 'cannot ');
        }

        # Check if article can be published
        if($workflow->can($article , 'publish')){
            $workflow->apply($article,"publish");
            # insert Into database
            $this->save($article);
        }

        # get the redirect
        $redirect = $request->get('redirect') ?? 'index';

        # redirect
        return $this->redirectToRoute($redirect);
    }
}