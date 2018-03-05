<?php

namespace App\Controller\Security;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * User register
     * @Route (
     *     "/register.html",
     *      name="security_register",
     *     methods={"GET","POST"}
     *     )
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        # New user registration
        $author = new Author();

        # Symfony automatically serialize it
        $author->setRoles('ROLE_MEMBER');

        # create the user form
        $form = $this->createForm(AuthorType::class, $author);

        # post data manager
        $form->handleRequest($request);

        # check form datas
        if ($form->isSubmitted() && $form->isValid()) {
            # Check password verification
            if ($author->getPassword() != $author->getPasswordCheck()) {
                # TODO : email de validation
                //$form->get('passwordCheck')->addError(new FormError('password does not match'));
                //return $this->redirectToRoute('security_connexion', ['register' => 'success']);
            }

            # Check firstname-lastname
            /*
            if ($author->getPassword() != $author->getPasswordCheck()) {
                # TODO : firstname-lastname
                if first-name-lastname slugified exist then
                    add a number
            }
            */

            # password encryption
            $password = $passwordEncoder->encodePassword($author, $author->getPassword());
            $author->setPassword($password);

            # insert into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($author);
            $eManager->flush();

            # redirect user
            return $this->redirectToRoute('security_connexion', ['register' => 'success']);
        }

        # Display form view
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * User login
     * @Route(
     *     "/login.html",
     *      name="security_connexion"
     * )
     */
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils)
    {
        # Récupération du message d'erreur s'il y en a un.
        $error = $authenticationUtils->getLastAuthenticationError();

        # Dernier email saisie par l'utilisateur.
        $lastEmail = $authenticationUtils->getLastUsername();

        # Affichage du Formulaire
        return $this->render('security/login.html.twig', array(
            'last_email' => $lastEmail,
            'error' => $error
        ));
    }


    /**
     * User password recover
     * @Route(
     *     "/send_recover.html",
     *      name="security_send_recover"
     * )
     */
    public function sendRecover(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $email='test@frogg.fr';

        #getUserFromEmail
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);
        $author = $reposirotyAuthor->findOneBy(['email'=>$email]);

        # create a token
        $author->setToken(bin2hex(random_bytes(100)));

        # insert into database
        $eManager = $this->getDoctrine()->getManager();
        $eManager->persist($author);
        $eManager->flush();

        # Affichage du Formulaire
        # redirect user
        return $this->redirectToRoute('security_connexion', ['register' => 'recover']);
    }

    /**
     * User password recover
     * @Route(
     *     "/recover.html",
     *      name="security_recover"
     * )
     */
    public function recover(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $token='0383f674098c3c8609ef5227520767f497213c5628b1bb3380b5c1bf847ce5ec99ebcc29fc1b20f54f1c2e1f880d4efcdde0';
        $email='test@frogg.fr';


        # getUserFromEmail
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);
        $author = $reposirotyAuthor->findOneBy(['email'=>$email,'token'=>$token]);

        #
        $now = new \DateTime();
        $now->diff($author->getTokenValidity());

        # insert into database
        $eManager = $this->getDoctrine()->getManager();
        $eManager->persist($author);
        $eManager->flush();

        # Affichage du Formulaire
        # redirect user
        return $this->redirectToRoute('security_connexion', ['register' => 'recover']);
    }


}
