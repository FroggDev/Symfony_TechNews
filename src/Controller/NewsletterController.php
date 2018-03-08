<?php
namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class NewsletterController
 * @package App\Controller
 */
class NewsletterController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsletter()
    {
        # init newsletter object
        $newsLetter = new Newsletter();

        # Création du Formulaire
        $form = $this->createForm(NewsletterType::class, $newsLetter);

        # Affichage du Formulaire Newsletter
        return $this->render('newsletter/modalForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sideNewsletter()
    {
        # init newsletter object
        $newsLetter = new Newsletter();

        # Création du Formulaire
        $form = $this->createForm(NewsletterType::class, $newsLetter);

        # Affichage du Formulaire Newsletter
        return $this->render('newsletter/sidebarForm.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route(
     *     "/newsletter/register.html",
     *     name="newsletter_register"
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerNewsletter(Request $request)
    {
        # init newsletter object
        $newsLetter = new Newsletter();

        # Création du Formulaire
        $form = $this->createForm(NewsletterType::class, $newsLetter);

        $form->handleRequest($request);

        # check form datas
        if ($form->isSubmitted()) {
            # insert into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($newsLetter);
            $eManager->flush();

            return $this->redirect($request->getReferer(), ['newsletter' => 'registered']);
        }
    }
}
