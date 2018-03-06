<?php
namespace App\Controller;

use App\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class NewsletterController
 * @package App\Controller
 */
class NewsletterController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsletter()
    {
        # Création du Formulaire
        $form = $this->createForm(NewsletterType::class);

        # Affichage du Formulaire Newsletter
        return $this->render('components/_newsletter.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
