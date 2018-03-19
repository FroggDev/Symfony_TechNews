<?php

namespace App\Controller\Security;

use App\Common\Traits\Comm\MailerTrait;
use App\Common\Traits\Database\DatabaseTrait;
use App\Entity\Author;
use App\Form\AuthorPasswordType;
use App\Form\AuthorRecoverType;
use App\Form\AuthorType;
use App\SiteConfig;
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

    use DatabaseTrait;

    /**
     * SecurityController constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * User login
     * @Route(
     *     "/{_locale}/login.html",
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

        # add error if exist
        if ($error) {
            $this->addFlash(
                'error',
                $error->getMessage()
            );
        }

        # Affichage du Formulaire
        return $this->render('security/login.html.twig', array(
            'last_email' => $lastEmail
        ));
    }


    /**
     * User register
     * @Route (
     *     "/{_locale}/register.html",
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

        # create the user form
        $form = $this->createForm(AuthorType::class, $author);

        # post data manager
        $form->handleRequest($request);

        # check form datas
        if ($form->isSubmitted() && $form->isValid()) {
            # password encryption
            $password = $passwordEncoder->encodePassword($author, $author->getPassword());
            $author
                ->setPassword($password)
                ->setToken();

            # insert into database
            $this->save($author);

            # send the mail
            $this->send(
                SiteConfig::SECURITYMAIL,
                $author->getEmail(),
                'mail/registration.html.twig',
                SiteConfig::SITENAME . ' - Validation mail',
                $author
            );

            # redirect user
            $this->addFlash(
                'success',
                'Congratulation your account has been created !<br>An email has been sent to validate your account registration.'
            );

            # redirect user
            return $this->redirectToRoute('security_connexion');
        }

        # Display form view
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * User registration validation
     * @Route(
     *     "/{_locale}/register/validation.html",
     *      name="register_validation"
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerValidation(Request $request)
    {
        # getUserFromEmail
        $reposirotyAuthor = $this->getDoctrine()->getRepository(Author::class);
        $author = $reposirotyAuthor->findOneBy(
            [
                'email' => $request->query->get('email'),
                'token' => $request->query->get('token')
            ]
        );

        # check if datas are valid
        if ($this->checkAccountStatus($author, ['checkAlreadyValidated'])) {
            # remove token and enable account
            $author
                ->removeToken()
                ->setActive();

            # save into database
            $this->save($author);

            # redirect user
            $this->addFlash(
                'success',
                'Your account  has been validated !<br>You can now login to the application.'
            );
        }

        # redirect page
        return $this->redirectToRoute('security_connexion');
    }


    /**
     * User password recover form
     * @Route(
     *     "/{_locale}/recover/request.html",
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
            if (!$this->checkIfExist($author)) {
                return $this->redirectToRoute('security_recover', ['last_email' => $email]);
            }

            # create a token
            $author->setToken();

            # save into database
            $this->save($author);

            # send the mail
            $this->send(
                SiteConfig::SECURITYMAIL,
                $author->getEmail(),
                'mail/recover.html.twig',
                SiteConfig::SITENAME . ' - Password recovery',
                $author
            );

            $this->addFlash(
                'success',
                'An email has been sent to your mailbox'
            );
            # redirect user
            return $this->redirectToRoute('security_connexion');
        }

        # Display form view
        return $this->render('security/recover.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * User password recover
     * @Route(
     *     "/{_locale}/recover/validation.html",
     *      name="recover_validation"
     * )
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function recoverValidation(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        # getUserFromEmail
        $repositoryArticle = $this->getDoctrine()->getRepository(Author::class);
        $author = $repositoryArticle->findOneBy(
            [
                'email' => $request->query->get('email'),
                'token' => $request->query->get('token')
            ]
        );

        # check if request is valid
        if (!$this->checkAccountStatus($author, ['checkIfTokenExpired'])) {
            return $this->redirectToRoute('security_connexion');
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

            # save into database
            $this->save($author);

            # Add message
            $this->addFlash(
                'success',
                'Password has been changed !<br>You can now login to the application'
            );

            # redirect user
            return $this->redirectToRoute('security_connexion');
        }

        # Add message
        $this->addFlash(
            'success',
            'You can change now your password'
        );

        # Display form view
        return $this->render('security/changepassword.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Author $author
     * @param array $extraCheck
     * @return bool
     */
    private function checkAccountStatus(Author $author, array $extraCheck = []): bool
    {
        # author not found
        if (!$this->checkIfExist($author)) {
            return false;
        }

        # checkif author is banned
        if ($author->isBanned()) {
            $this->addFlash(
                'error',
                'This account is banned.'
            );
            return false;
        }

        # checkif author is closed
        if ($author->isClosed()) {
            $this->addFlash(
                'error',
                'This account has been closed'
            );
            return false;
        }

        # Extra custom check
        foreach ($extraCheck as $check) {
            if (!$this->$check($author)) {
                return false;
            }
        }

        # else it is ok
        return true;
    }

    /**
     * @param Author $author
     * @return bool
     */
    private function checkIfExist(Author $author): bool
    {
        #check if author exist
        if (!$author) {
            $this->addFlash(
                'error',
                'Email not found.'
            );
            return false;
        }

        # else it is ok
        return true;
    }

    /**
     * @param Author $author
     * @return bool
     */
    private function checkAlreadyValidated(Author $author): bool
    {
        # checkif account already registered
        if ($author->isEnabled()) {
            $this->addFlash(
                'notice',
                'Account is already activated.'
            );
            return false;
        }
        # else it is ok
        return true;
    }

    /**
     * @param Author $author
     * @return bool
     */
    private function checkIfTokenExpired(Author $author): bool
    {
        # checkif account already registered
        if ($author->isTokenExpired()) {
            $this->addFlash(
                'error',
                'The request for password recovery has expired.'
            );
            return false;
        }
        # else it is ok
        return true;
    }

}
