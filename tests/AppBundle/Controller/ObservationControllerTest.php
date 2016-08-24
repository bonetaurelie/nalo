<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObservationControllerTest extends WebTestCase
{
	const MY_OBSERVATIONS_ROUTE = '/mes-observations';
	const ADD_OBSERVATION_ROUTE = '/ajouter-une-observation';
	const EDIT_OBSERVATION_ROUTE = '/edit-une-observation/{id}';
	const DELETE_OBSERVATION_ROUTE = '/supprimer-une-observation/{id}';

	/**
	 * Récupération d'un compte utlisateur amateur
	 *
	 * @param Client $client
	 */
	public function connexionCompteAmateur(Client $client)
	{
		$crawler = $client->request('GET', '/connexion');

		$form = $crawler->filter("form")->form();

		$form['_username'] = 'test.amateur@test.fr';
		$form['_password'] = 'test1A-';

		$client->submit($form);
	}


	/**
	 * test l'accès au détail d'une observation via la page d'accueil
	 */
	public function testDetailObsAccessByHomePage()
	{
		echo "test l'accès au détail d'une observation via la page d'accueil \r\n";

		$client = static::createClient();

		$crawler = $client->request('GET', '/');//accès page d'accueil

		$obLink = $crawler->filter("#observations-list li")->eq(0)->filter("h4 a")->link();

		$client->click($obLink);


		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}


    /**
     * Vérifie que la page qui liste les observations écrite par l'utilisateur
     */
    public function testAccessMyObservation()
    {
	    $client = static::createClient();

	    $this->connexionCompteAmateur($client);

	    $url = self::MY_OBSERVATIONS_ROUTE;

        echo "accès url : ".$url."\r\n";
	    $client->request('GET', $url);
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testAccessAddObservationPage()
    {
	    $client = static::createClient();

	    $this->connexionCompteAmateur($client);

	    $url = self::MY_OBSERVATIONS_ROUTE;

	    echo "accès url : ".$url."\r\n";
	    $client->request('GET', $url);
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

	public function testAccessEditObservationPage()
	{
		$client = static::createClient();

		$this->connexionCompteAmateur($client);

		$crawler =  $client->request('GET', '/mes-observations');


		$editLInk = $crawler->filter("a[title=Modifier]")->link();

		$client->click($editLInk);


		$this->assertEquals(200, $client->getResponse()->getStatusCode());

	}
}
