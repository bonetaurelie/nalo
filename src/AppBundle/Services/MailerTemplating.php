<?php

namespace AppBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class MailerTemplating
 *
 * Cette classe permet d'envoyer des e-mails avec un template twig améliorant le visuel de l'e-mail
 *
 * @package AppBundle\Services
 * @author NICOLAS PIN <pin.nicolas@free.fr>
 *
 */
class MailerTemplating
{
    private $mailer;
    private $templating;

    /**
     * MailerTemplating constructor.
     * @param \Swift_Mailer $mailer le service d'envoi d'email
     * @param EngineInterface $templating le service de templating
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /***
     * Send
     *
     * Envoie un e-mail avec un template html
     *
     * @param array $bodyMessage le coprs du message
     * @param string $subject le sujet du message
     * @param string $from l'e-mail qui envoie
     * @param string $to l'e-mail qui reçoit
     * @param string $template le template html
     * @param string $contentType le type de contenu envoyé
     * @return int nombre d'e-mail envoyé
     */
    public function send(array $bodyContents, $subject, $from, $to, $template, $contentType = 'text/html'){
        $body = $this->templating->render($template, $bodyContents);


        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body);

        return $this->mailer->send($message);
    }
}