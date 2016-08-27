<?php

namespace Tests\AppBundle\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test le controller principal : page d'accueil, association, mentions légales, contact
 *
 * Class MainControllerTest
 * @package Tests\AppBundle\Functional\Controller
 *
 * @author nicolas PIN <pin.nicolas@free.fr>
 * @version 2016-08-27
 *
 */
class MainControllerTest extends WebTestCase
{
	const HOME_LINK = '/';
	const SUBSCRIBE_LINK = '/inscription';
	const CONNECT_LINK = '/connexion';
	const ASSOCIATION_LINK = '/association';
	const LEGAL_MENTIONS_LINK = '/mentions';
	const SEARCH_LINK = '/recherche';
	const CONTACT_LINK = '/contact';


    /**
     * Page accueil
     * Vérifie que la page d'accueil s'affiche bien avec le bon titre
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', self::HOME_LINK);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('OBSERVEZ, PARTAGEZ ET PARTICIPEZ', $crawler->filter('h1')->text());
    }

	/**
	 * Page accueil
	 * Teste le lien pour accéder au formulaire d'inscription sans être connecter
	 */
	public function testRegistrationLinkIndexWithoutLogged()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::HOME_LINK);

		$link =  $crawler->selectLink("Je m'inscris !")->link();

		$crawler = $client->click($link);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains('INSCRIPTION', $crawler->filter('h1')->text());
	}

	/**
	 * Page accueil
	 * Teste le lien pour accéder au formulaire d'inscription
	 */
	public function testRegistrationLinkIndexLogged()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$crawler = $client->request('GET', self::HOME_LINK);

		$link =  $crawler->selectLink("Je m'inscris !")->link();

		$client->click($link);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains('MON PROFIL', $crawler->filter('h1')->text());
	}

	/**
	 * Page accueil
	 * Teste le lien pour accéder au formulaire de recherche
	 */
	public function testSearchLinkIndex()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::HOME_LINK);

		$link =  $crawler->selectLink("Effectuer une recherche")->link();

		$crawler = $client->click($link);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains('RECHERCHE', $crawler->filter('h1')->text());
	}

	/**
	 * Page accueil
	 * Teste le lien pour accéder au formulaire de saisi d'une observation sans être connecter
	 * Doit être redirigé vers la page de connexion
	 */
	public function testEnterObservationLinkIndexWithoutLogged()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', self::HOME_LINK);

		$link =  $crawler->selectLink("Saisir une observation")->link();

		$client->click($link);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains('CONNEXION', $crawler->filter('h1')->text());
	}

	/**
	 * Page accueil
	 * Teste le lien pour accéder au formulaire de saisi d'une observation en étant connecter
	 */
	public function testEnterObservationLinkIndexLogged()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$crawler = $client->request('GET', self::HOME_LINK);

		$enterObservationLink =  $crawler->selectLink("Saisir une observation")->link();

		$crawler = $client->click($enterObservationLink);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains("SAISIE D'UNE OBSERVATION", $crawler->filter('h1')->text());
	}

	/**
	 * Page accueil
	 * Teste la présence de la liste des dernières observations
	 */
	public function testPresenceElementOnObservationsList(){
		$client = static::createClient();

		$crawler = $client->request('GET', self::HOME_LINK);

		$this->assertContains("DERNIÈRES OBSERVATIONS", $crawler->filter('h2')->eq(1)->text());
		$this->assertContains("Derniers oiseaux observés", $crawler->filter('h3')->text());

		$this->assertGreaterThan(0, $crawler->filter('#observations-list > li')->count());


		$this->assertRegExp("/[\s\S]/", $crawler->filter('#observations-list > li')->eq(0)->attr('data-title'));
		$this->assertRegExp("/[\s\S]/", $crawler->filter('#observations-list > li')->eq(0)->attr('data-longitude'));
		$this->assertRegExp("/[\s\S]/", $crawler->filter('#observations-list > li')->eq(0)->attr('data-latitude'));

		$link = $crawler->filter('#observations-list > li')->eq(0)->filter('a')->link();

		$crawler = $client->click($link);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains("Observation du", $crawler->filter('h1')->text());
	}


	/**
	 * Page association
	 *
	 * Vérifie que la page de présentation de l'association s'affiche bien
	 */
    public function testAssociaton(){
	    $client = static::createClient();

	    $crawler = $client->request('GET', self::ASSOCIATION_LINK);

	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


	/**
	 * Vérifie que la page des mentions légales s'affiche bien
	 */
	public function testMentions(){
		$client = static::createClient();

		$crawler = $client->request('GET', self::LEGAL_MENTIONS_LINK);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
//	    $this->assertContains('NOS AMIS LES OISEAUX', $crawler->filter('.accueil h1')->text());
	}

	/**
	 * Vérifie que la page contact s'affiche bien
	 */
	public function testContact(){
		$client = static::createClient();
		$crawler = $client->request('GET', self::CONTACT_LINK);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$heading = $crawler->filter('h1')->eq(0)->text();
		$this->assertEquals('CONTACTER L\'ASSOCIATION', $heading);
	}

	/**
	 * Vérifie que la page de recherche s'affiche bien
	 */
	public function testSearch(){
		$client = static::createClient();
		$crawler = $client->request('GET', self::SEARCH_LINK);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$heading = $crawler->filter('h1')->eq(0)->text();
		$this->assertEquals('RECHERCHE', $heading);
	}

	/**
	 * Récupération d'un compte utlisateur
	 *
	 * @todo à amélioration : créer une class Client commune à tout les tests
	 *
	 * @param Client $client
	 */
	public function connexionCompte(Client $client)
	{
		$crawler = $client->request('GET', self::CONNECT_LINK);

		$form = $crawler->filter("form")->form();

		$utilisateur = ['email' => 'test.amateur@test.fr', 'password' => 'test1A-'];

		$form['_username'] = $utilisateur['email'];
		$form['_password'] = $utilisateur['password'];

		$client->submit($form);
	}
}
