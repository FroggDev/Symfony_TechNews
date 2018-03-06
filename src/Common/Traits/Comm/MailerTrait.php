<?php
namespace App\Common\Traits\Comm;

/**
 * Trait MailerTrait
 * @package App\Common\Traits\Comm
 */
trait MailerTrait
{
    /**
     * @var \Swift_Mailer $mailer $mailer
     */
    private $mailer;

    /**
     * @param string $from
     * @param string $to
     * @param string $template
     * @param string $subject
     * @param $data
     *
     * @see https://symfony.com/doc/current/email/dev_environment.html
     * @see  Controller & injection __construct(\Swift_Mailer $mailer)
     */
    public function send(string  $from, string $to, string $template, string $subject, $data)
    {

        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    $template,
                    array('data' => $data)
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->mailer->send($message);
    }
}
