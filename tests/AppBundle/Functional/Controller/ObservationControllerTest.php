<?php

namespace Tests\AppBundle\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;


class ObservationControllerTest extends WebTestCase
{
	const MY_OBSERVATIONS_ROUTE = '/mes-observations';
	const ADD_OBSERVATION_ROUTE = '/ajouter-une-observation';
	const EDIT_OBSERVATION_ROUTE = '/edit-une-observation/{id}';
	const DELETE_OBSERVATION_ROUTE = '/supprimer-une-observation/{id}';
	const OBSERVATIONS_TO_VALIDATE_ROUTE = '/observations-a-valider';
	const CONNECTION_ROUTE = '/connexion';


	const UTILISATEURS_LIST = [
		'ROLE_USER' => [
			'email' => 'test.amateur@test.fr',
			'password' => 'test1A-'
		],
		'ROLE_PRO' => [
				'email' => 'test.professionnel@test.fr',
				'password' => 'test1P-'
		]
	];

	/**
	 * @var EntityManager
	 */
	private $_em;


	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->_em->beginTransaction();
	}

	/**
	 * Rollback changes.
	 */
	public function tearDown()
	{
		$this->_em->rollback();
	}

	/**
	 * Récupération d'un compte utlisateur
	 *
	 * @param Client $client
	 */
	public function connexionCompte(Client $client, $role = 'ROLE_USER')
	{
		$crawler = $client->request('GET', self::CONNECTION_ROUTE);

		$form = $crawler->filter("form")->form();

		$utilisateur = self::UTILISATEURS_LIST[$role];

		$form['_username'] = $utilisateur['email'];
		$form['_password'] = $utilisateur['password'];

		$client->submit($form);
	}

	/*****************************************************************************************************************/
	/************************************************ Tests affichage ************************************************/
	/*****************************************************************************************************************/

	/**
	 * Vérifie que la page mes observations ne soit pas accessible si aucun type d'utilisateur n'est connecté
	 * Elle doit redirigée vers la page de connexion
	 */
	public function testAccessMyObservationsNotLogger()
	{
		$client = static::createClient();

//		$this->connexionCompte($client);

		$url = self::MY_OBSERVATIONS_ROUTE;

		$client->request('GET', $url);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains("CONNEXION", $crawler->filter("h1")->text());
	}

    /**
     * Vérifie que la page mes observations soit accessible par un utilisateur amateur
     */
    public function testAccessMyObservationsRoleUser()
    {
	    $client = static::createClient();

	    $this->connexionCompte($client);

	    $url = self::MY_OBSERVATIONS_ROUTE;

	    $crawler = $client->request('GET', $url);
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());


	    $this->assertContains("MES OBSERVATIONS", $crawler->filter("h1")->text());
    }

	/**
	 * Page mes observation
	 * Test si le lien vers l'ajout d'une observation fonctionne
	 */
	public function testMyObservationAccessAddObservation()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$url = self::MY_OBSERVATIONS_ROUTE;

		$crawler = $client->request('GET', $url);

		$link = $crawler->selectLink('Ajouter une observation')->link();

		$crawlerLink = $client->click($link);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains("SAISIE D'UNE OBSERVATION", $crawlerLink->filter("h1")->text());
	}

	/**
	 * Page mes observation
	 * Test si le lien vers l'édition d'une observation fonctionne
	 */
	public function testMyObservationAccessEditObservation()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$url = self::MY_OBSERVATIONS_ROUTE;

		$crawler = $client->request('GET', $url);

		$link = $crawler->filter("table a[title=Modifier]")->link();

		$crawlerLink = $client->click($link);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$this->assertContains("MODIFIER VOTRE OBSERVATION", $crawlerLink->filter("h1")->text());
	}

	/**
	 * Page mes observation
	 * Test si le lien vers la suppression d'une observation fonctionne
	 */
	public function testMyObservationAccessDeleteObservation()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$url = self::MY_OBSERVATIONS_ROUTE;

		$crawler = $client->request('GET', $url);

		$link = $crawler->filter("table a[title=Supprimer]")->link();

		$client->click($link);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains("supprimée avec succès", $crawler->filter(".alert")->text());

	}






	/************************************************ UTILISATEUR PRO ************************************************/


	/**
	 * Test l'accès à la page mes observations avec un compte pro
	 */
	public function testAccessMyObservationsWithRolePro()//ToValidate
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_PRO');

		$url = self::MY_OBSERVATIONS_ROUTE;

		$client->request('GET', $url);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test l'accès à la page des observations à valider par un utilisateur amateur
	 * L'accès doit être refusé code http 403
	 */
	public function testAccessObservationsToValidateByRoleUser()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_USER');

		$url = self::OBSERVATIONS_TO_VALIDATE_ROUTE;

		$client->request('GET', $url);
		$this->assertEquals(403, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test l'accès à la page des observations à valider par un utilisateur pro
	 * L'accès doit être accepté
	 */
	public function testAccessObservationsToValidateByRolePro()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_PRO');

		$url = self::OBSERVATIONS_TO_VALIDATE_ROUTE;

		$client->request('GET', $url);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}


	/**
	 * Test l'accès à la page des observations à valider par un utilisateur pro
	 * L'accès doit être accepté
	 */
	public function testAccessObservationToValidateByRolePro()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_PRO');

		$url = self::OBSERVATIONS_TO_VALIDATE_ROUTE;

		$crawler = $client->request('GET', $url);

		$validateLInk = $crawler->filter("a[title=Valider]")->link();

		$client->click($validateLInk);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/*****************************************************************************************************************/
	/*********************************************** Tests formulaires ***********************************************/
	/*****************************************************************************************************************/

	/**
	 * Test l'ajout d'une observation avec tout les champs vides, cahque champ doit retourné une erreur
	 */
	public function testAddObservationEmptyFields()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_USER');

		$crawler = $client->request('GET', self::ADD_OBSERVATION_ROUTE);

		$form =$crawler->filter('form')->form();

		$form->disableValidation();

		$form['observation[datetimeObservation][date]'] = '';
		$form['observation[datetimeObservation][time]'] = '';
		$form['observation[department]'] = '';
		$form['observation[city]'] = '';
		$form['observation[locality]'] = '';
		$form['observation[species]'] = '';

		$form['observation[nbIndividual]'] = '';

		$form['observation[comment]'] = '';

		$form['observation[longitude]'] = 0;
		$form['observation[latitude]'] = 0;

		$crawler = $client->submit($form);

		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#observation_datetimeObservation + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#observation_department + .help-block ul li")->text());
		//le champ "city" utilise un FormType spécial et donc il faut un petit peu changer l'accès a la class helper
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#observation_city")->parents('div > .help-block ul li')->text());
		//le champ "species" utilise un FormType spécial et donc il faut un petit peu changer l'accès a la class helper
		$speciesCrawlerToTest = $crawler->filter("#observation_species")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("Cette valeur ne doit pas être vide.", $speciesCrawlerToTest);
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#observation_nbIndividual")->parents('div')->parents('div > .help-block ul li')->text());
	}

	/**
	 * Test l'ajout d'une observation avec des champs invalides, cahque champ doit retourné une erreur
	 */
	public function testAddObservationInvalidFields()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_USER');

		$crawler = $client->request('GET', self::ADD_OBSERVATION_ROUTE);

		$form =$crawler->filter('form')->form();

		$form->disableValidation();

		$form['observation[datetimeObservation][date]'] = '40-60-458789';
		$form['observation[datetimeObservation][time]'] = '40:80';
		$form['observation[department]'] = 99999;
		$form['observation[city]'] = 99999;
		$form['observation[locality]'] = '';
		$form['observation[species]'] = 99999;

		$form['observation[nbIndividual]'] = 0;

		$form['observation[comment]'] = '';

		$form['observation[longitude]'] = 4564646545;
		$form['observation[latitude]'] = 4564646545;

		$crawler = $client->submit($form);

		$this->assertContains("Cette valeur n'est pas valide.", $crawler->filter("#observation_datetimeObservation + .help-block ul li")->text());
		$this->assertContains("Cette valeur n'est pas valide.", $crawler->filter("#observation_department + .help-block ul li")->text());
		//le champ "city" utilise un FormType spécial et donc il faut un petit peu changer l'accès a la class helper
		$this->assertContains(" Cette valeur n'est pas valide.", $crawler->filter("#observation_city")->parents('div > .help-block ul li')->text());
		//le champ "species" utilise un FormType spécial et donc il faut un petit peu changer l'accès a la class helper
		$speciesCrawlerToTest = $crawler->filter("#observation_species")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains(" Cette valeur n'est pas valide.", $speciesCrawlerToTest);
		$this->assertContains("Cette valeur doit être supérieure ou égale à 1.", $crawler->filter("#observation_nbIndividual")->parents('div')->parents('div > .help-block ul li')->text());
	}
}
