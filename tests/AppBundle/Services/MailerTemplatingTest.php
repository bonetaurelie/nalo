<?php

//namespace Tests\AppBundle\Services;
//
//use OC\CoreBundle\Services\MailerTemplating;
//use Symfony\Component\Form\Test\TypeTestCase;
//
//class MailerTemplatingTest extends Test
//{
//    private $mailer;
//
////    public function setUp()
////    {
////        //start the symfony kernel
////        $kernel = static::createKernel();
////        $kernel->boot();
////
////        //get the DI container
////        self::$container = $kernel->getContainer();
////
////        //now we can instantiate our service (if you want a fresh one for
////        //each test method, do this in setUp() instead
////        $this->mailer =  self::$container->get('oc_core.mailer_templating');
////
//////        self::bootKernel();
//////        $this->mailer = static::$kernel->getContainer()->get('oc_core.mailer_templating');
////    }
//
//    /**
//     *
//     */
//    public function testInvalidFromMail()
//    {
//        $mailer = $this->getMock('mailer');
//        // Configure your mock here.
//        static::$kernel->setKernelModifier(function($kernel) use ($mailer) {
//            $kernel->getContainer()->set('oc_core.mailer_templating', $mailer);
//        });
//
//
//        $mailer->send(array(), 'test', 'test','test', '@OCCore/Email/contact.html.twig');
//    }
//}