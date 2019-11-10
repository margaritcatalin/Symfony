<?php
namespace App\Mailer;
use App\Entity\User;
class Mailer
{
    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment $twig
     */
    private $twig;
    /**
     * @var string $mailFrom
     */
    private $mailFrom;
    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        string $mailFrom
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }
    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' =>$user
        ]);
        $message = (new \Swift_Message())
            ->setSubject('Blog Registration')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }
} 