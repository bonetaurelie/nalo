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
    }
}
