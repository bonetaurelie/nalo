<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('NOS AMIS LES OISEAUX', $crawler->filter('.accueil h1')->text());
    }

    public function testContact(){
        $client = static::createClient();
        $crawler = $client->request('GET','/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $heading = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('CONTACTER L\'ASSOCIATION', $heading);

//        $formData = array(
//            'prenom',
//            'nom',
//            'email',
//            'message',
//        );
//
//        $form = $crawler->filter('form')->form(array(
//            'form[prenom]' => 'test',
//            'form[nom]' => 'test',
//            'form[email]' => 'test@test.fr',
////            'form[recaptcha]' => true,
////            'form[message]' => '',
//        ));
//        $crawler = $client->submit($form);
//        $this->assertTrue($crawler->filter('.error_list')->count() === 0);

        $form = $crawler->filter('form')->form();

        $form->setValues(array(
            'prenom' => 'test',
            'nom' => 'test',
            'email' => 'test@test.fr',
            'message' => 'test@test.fr',
        ));

        $client->submit($form);
        $response = $client->getResponse();
        //$this->assertContains('erreur', $response->getContent());
    }
}
