<?php

namespace App\Controller\Security;

use App\Common\Traits\Comm\MailerTrait;
use App\Entity\Author;
use App\Form\AuthorPasswordType;
use App\Form\AuthorRecoverType;
use App\Form\AuthorType;
use App\Mail\AuthorCreationMail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller\Security
 */
class SecurityController extends Controller
{

    use MailerTrait;

    /**
     * SecurityController constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer=$mailer;
    }

    /**
     * User login
     * @Route(
     *     "/login.html",
     *      name="security_connexion"
     * )
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connexion(AuthenticationUtils $authenticationUtils)
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
     * User register
     * @Route (
     *     "/register.html",
     *      name="security_register",
     *     methods={"GET","POST"}
     *     )
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
            /*if ($author->getPassword() != $author->getPasswordCheck()) {
                # TODO : email de validation
                //$form->get('passwordCheck')->addError(new FormError('password does not match'));
                //return $this->redirectToRoute('security_connexion', ['register' => 'success']);
            }*/

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
            $author
                ->setPassword($password)
                ->setInactive()
                ->setToken();

            # insert into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($author);
            $eManager->flush();

             # send the mail
            $this->send('technews@frogg.fr', $author->getEmail(), 'mail/registration.html.twig', 'TechNews - Validation mail', $author);

            # redirect user
            return $this->redirectToRoute('security_connexion', ['register' => 'success']);
        }

        # Display form view
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * User registration validation
     * @Route(
     *     "/register/validation.html",
     *      name="register_validation"
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerValidation(Request $request)
    {
        # get request infos
        $email = $request->query->get('email');
        $token = $request->query->get('token');

        # getUserFromEmail
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);
        $author = $reposirotyAuthor->findOneBy(
            [
                'email' => $email,
                'token' => $token
            ]
        );

        # author not found
        if (!$author) {
            return $this->redirectToRoute('security_connexion', ['register' => 'notfound']);
        }

        # account already registered
        if ($author->isEnabled) {
            return $this->redirectToRoute('security_connexion', ['register' => 'actived']);
        }

        # checkif author is banned
        if ($author->isBanned()) {
            return $this->redirectToRoute('security_connexion', ['register' => 'banned']);
        }

        # checkif author is banned
        if ($author->isClosed()) {
            return $this->redirectToRoute('security_connexion', ['register' => 'closed']);
        }

        # remove token and enable account
        $author->removeToken()
            ->setActive();

        # update database
        $eManager = $this->getDoctrine()->getManager();
        $eManager->persist($author);
        $eManager->flush();

        # redirect user
        return $this->redirectToRoute('security_connexion', ['register' => 'validated']);
    }


    /**
     * User password recover form
     * @Route(
     *     "/recover/request.html",
     *      name="security_recover"
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function recoverRequest(Request $request)
    {
        # New user registration
        $author = new Author();

        # create the user form
        $form = $this->createForm(AuthorRecoverType::class, $author);

        # post data manager
        $form->handleRequest($request);

        # check form datas
        if ($form->isSubmitted()) {
            # get repo author
            $repositoryArticle = $this->getDoctrine()->getRepository(Author::class);

            #get posted email
            $email = $form->getData()->getEmail();

            #get author from email
            $author = $repositoryArticle->findOneBy(['email' => $email]);

            # author not found
            if (!$author) {
                # redirect user
                return $this->redirectToRoute('security_recover', ['register' => 'notfound', 'last_email' => $email]);
            }

            # create a token
            $author->setToken();

            # insert into database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($author);
            $eManager->flush();

            # send the mail
            $this->send('technews@frogg.fr', $author->getEmail(), 'mail/recover.html.twig', 'TechNews - Password recovery', $author);

            # redirect user
            return $this->redirectToRoute('security_connexion', ['register' => 'recovered']);
        }

        # Display form view
        return $this->render('security/recover.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * User password recover
     * @Route(
     *     "/recover/validation.html",
     *      name="recover_validation"
     * )
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function recoverValidation(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        # get request infos
        $email = $request->query->get('email');
        $token = $request->query->get('token');

        # getUserFromEmail
        $repositoryArticle = $this->getDoctrine()->getRepository(Author::class);
        $author = $repositoryArticle->findOneBy(
            [
                'email' => $email,
                'token' => $token
            ]
        );

        # author not found
        if (!$author) {
            return $this->redirectToRoute('security_connexion', ['register' => 'notfound']);
        }

        # checkif token has expired
        if ($author->isTokenExpired()) {
            return $this->redirectToRoute('security_connexion', ['register' => 'expired']);
        }

        # checkif author is banned
        if ($author->isBanned()) {
            return $this->redirectToRoute('security_connexion', ['register' => 'banned']);
        }

        # checkif author is banned
        if ($author->isClosed()) {
            return $this->redirectToRoute('security_connexion', ['register' => 'closed']);
        }


        # create the user form
        $form = $this->createForm(AuthorPasswordType::class, $author);

        # post data manager
        $form->handleRequest($request);

        # check form datas
        if ($form->isSubmitted()) {
            # password encryption
            $password = $passwordEncoder->encodePassword($author, $author->getPassword());

            # change password into database
            $author
                ->setPassword($password)
                ->removeToken()
                ->setActive();

            # update database
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($author);
            $eManager->flush();

            # redirect user
            return $this->redirectToRoute('security_connexion', ['register' => 'passechanged']);
        }

        # Display form view
        return $this->render('security/changepassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
