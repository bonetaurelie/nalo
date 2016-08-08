<?php

namespace Tests\AppBundle\Services;


use AppBundle\Services\MailerTemplating;

/**
 *
 * Test unitaire pour le service OC/CoreBundle/services/MailerTemplating
 * @todo il faut tester si une erreur est déclenché quand le sujet n'est pas renseigné
 * @todo il faut tester si une erreur est déclenché quand le template envoyé n'existe pas
 *
 * Class MailerTemplatingTest
 * @package Tests\AppBundle\Services
 */
class MailerTemplatingTest extends \PHPUnit_Framework_TestCase
{
    private $mailer;
    private $templating;

    /**
     * Charge les dépendance du service
     */
    public function setUp()
    {
        $this->mailer = $this->getMockBuilder('Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->templating = $this->getMockBuilder('Symfony\Component\Templating\EngineInterface')
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * Test si quand l'e-mail d'envoie est fausse ça retour bien une erreur
     * @expectedException        Swift_RfcComplianceException
     * @expectedExceptionMessage Address in mailbox given [test] does not comply with RFC 2822, 3.6.2.
     */
    public function testInvalidFromMail()
    {
        $mailerTemplating = new MailerTemplating($this->mailer, $this->templating);

        $mailerTemplating->send(array(), 'test', 'test','test', '@OCCore/Email/contact.html.twig');
    }

    /**
     * Test si quand l'e-mail de réception est fausse ça retour bien une erreur
     * @expectedException        Swift_RfcComplianceException
     * @expectedExceptionMessage Address in mailbox given [test] does not comply with RFC 2822, 3.6.2.
     */
    public function testInvalidToMail()
    {
        $mailerTemplating = new MailerTemplating($this->mailer, $this->templating);

        $mailerTemplating->send(array(), 'test', 'test@test.fr','test', '@OCCore/Email/contact.html.twig');
    }
}