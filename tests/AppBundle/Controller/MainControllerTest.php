<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * Vérifie que la page d'accueil s'affiche bien avec le bon titre
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('NOS AMIS LES OISEAUX', $crawler->filter('.accueil h1')->text());
    }

	/**
	 * Vérifie que la page de présentation de l'association s'affiche bien
	 */
    public function testAssiocaton(){
	    $client = static::createClient();

	    $crawler = $client->request('GET', '/association');

	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


	/**
	 * Vérifie que la page des mentions légales s'affiche bien
	 */
	public function testMentions(){
		$client = static::createClient();

		$crawler = $client->request('GET', '/mentions');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
//	    $this->assertContains('NOS AMIS LES OISEAUX', $crawler->filter('.accueil h1')->text());
	}

	/**
	 * Vérifie que la page contact s'affiche bien
	 */
	public function testContact(){
		$client = static::createClient();
		$crawler = $client->request('GET','/contact');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$heading = $crawler->filter('h1')->eq(0)->text();
		$this->assertEquals('CONTACTER L\'ASSOCIATION', $heading);
	}

	/**
	 * Vérifie que la page de recherche s'affiche bien
	 */
	public function testSearch(){
		$client = static::createClient();
		$crawler = $client->request('GET','/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$heading = $crawler->filter('h1')->eq(0)->text();
		$this->assertEquals('RECHERCHE', $heading);
	}
}
