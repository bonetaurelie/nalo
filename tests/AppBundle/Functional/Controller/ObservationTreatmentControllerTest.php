<?php

namespace Tests\AppBundle\Functional\Controller;

use AppBundle\Entity\locality\Department;
use AppBundle\Entity\Observation;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;


class ObservationTreatmentControllerTest extends WebTestCase
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

	/**
	 * @var string
	 */
	private $rootDir;

	private $fakeGoodObservationData;

	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->_em->beginTransaction();

		$this->rootDir = $kernel->getRootDir();

		$this->setFakeGoodObservation();
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


	public function setFakeGoodObservation()
	{
		$department = $this->getFakeDepartment();
		$city = $this->getFakeCity();
		$species = $this->getFakeSpecies();

		$this->fakeGoodObservationData = (object) [
			'date' => new \DateTime(),
			'department' => $department,
			'city' =>  $city,
			'species' =>  $species,
			'state' => Observation::STATE_STANDBY,
			'locality' => 'Domaine du verger',
			'nbIndividual' => 2,
			'comment' => 'test fonctionnel',
			'longitude' => $city->getLongitude(),
			'latitude' => $city->getLatitude(),
		];
	}

	public function getFakeDepartment()
	{
		return $this->_em->getRepository("AppBundle:locality\Department")->findOneByAdminName("Vaucluse");
	}

	public function getFakeCity()
	{
		return $this->_em->getRepository("AppBundle:locality\City")->findOneByAdminName("Le Thor");
	}

	public function getFakeSpecies()
	{
		return $this->_em->getRepository("AppBundle:Species")->findOneByFrenchName("Martinet de Cayenne");
	}

	public function getFakeListOfCity(Department $department)
	{
		return $this->_em->getRepository("AppBundle:locality\City")->findByDepartment($department);
	}


	/**
	 * Test l'ajout d'une observation avec des champs invalides, chaque champ doit retourné une erreur
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

	/**
	 * test ajout d'une observation avec données valides
	 */
	public function testAddObservationValidFields()
	{
		$client = $this->setAddValidObservation();

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContains("Merci d'avoir soumis une observation, elle sera validée dans les plus brefs délais", $crawler->filter(".alert")->text());
	}




	public function testGetAjaxCitysList()
	{
		$department = $this->getFakeDepartment();

		$crawler = $this->getSelectHTMLCitysList($department);

		$this->assertGreaterThan(0, $crawler->filter('option')->count());
	}


	private function getSelectHTMLCitysList($department)
	{
		$client = static::createClient();

		$crawler = $client->request('POST', '/anacona16_dependent_forms');
		$this->assertEquals('DependentFormsBundle:DependentForms:getOptions', $client->getRequest()->attributes->get('_controller'));

		return $client->request('POST', '/anacona16_dependent_forms', array(
			'entity_alias' => 'city_by_department',
			'parent_id' => $department->getId(),
			'empty_value' => 'Choisir une ville'
		), array(), array(
			'X-Requested-With' => 'XMLHttpRequest',
		));
	}


	public function testMyObservationAllInfoIsPresent()
	{
		$client = static::createClient();

		$this->connexionCompte($client);

		$url = self::MY_OBSERVATIONS_ROUTE;

		$crawler = $client->request('GET', $url);

		$obs = $this->fakeGoodObservationData;

		$formatter = \IntlDateFormatter::create(
			'fr_FR',
			\IntlDateFormatter::FULL,
			\IntlDateFormatter::NONE,
			'Europe/Paris',
			\IntlDateFormatter::GREGORIAN
		);

		//Vérifie si la date de l'observation est présente et formaté correctement
		$this->assertContains($formatter->format($obs->date), $crawler->filter('table > tbody')->text());
		//Vérifie si le nom de la ville est présent
		$this->assertContains($obs->city->getAdminName(), $crawler->filter('table > tbody')->text());
		//Vérifie si le nom de l'espèce est présent
		$this->assertContains($obs->species->getFrenchName(), $crawler->filter('table > tbody')->text());

		//Vérifie si une colonne statut est présente
		$this->assertContains("Statut", $crawler->filter('table > thead > tr > th')->eq(3)->text());

		//Vérifie si un bouton édition est présent (il doit l'être au moins une fois par rapport à l'observation ajouté précédemment
		$this->assertGreaterThan(0, $crawler->filter('table > tbody a[title=Modifier]')->count());

		//Vérifie si un bouton suppression est présent (il doit l'être au moins une fois par rapport à l'observation ajouté précédemment
		$this->assertGreaterThan(0, $crawler->filter('table > tbody a[title=Supprimer]')->count());
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



	public function setAddValidObservation($role = 'ROLE_USER')
	{
		$client = static::createClient();

		$this->connexionCompte($client, $role);

		$crawler = $client->request('GET', self::ADD_OBSERVATION_ROUTE);

		$form = $crawler->filter('form')->form();
		$form->disableValidation();

		$observation = $this->fakeGoodObservationData;


		$form['observation[datetimeObservation][date]'] = $observation->date->format('Y-m-d');
		$form['observation[datetimeObservation][time]'] = $observation->date->format('H:i');
		$form['observation[department]'] = $observation->department->getId();
		$form['observation[city]'] = $observation->city->getId();
		$form['observation[locality]'] = $observation->locality;
		$form['observation[species]'] = $observation->species->getId();

		$form['observation[nbIndividual]'] = $observation->nbIndividual;

		$form['observation[comment]'] = $observation->comment;

		$form['observation[longitude]'] = $observation->longitude;
		$form['observation[latitude]'] = $observation->latitude;

		$client->submit($form);

		return $client;
	}


	/************************************************ UTILISATEUR PRO ************************************************/

	public function setAddValidObservationByRolePro()
	{
		$client = $this->setAddValidObservation($role = 'ROLE_USER');

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$this->assertContainsOnly("Merci d'avoir soumis une observation", $crawler->filter(".alert")->text());
	}



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


	public function getAccessLinkObservationToValidateByRolePro()
	{
		$client = static::createClient();

		$this->connexionCompte($client, 'ROLE_PRO');

		$url = self::OBSERVATIONS_TO_VALIDATE_ROUTE;

		$crawler = $client->request('GET', $url);

		$validateLInk = $crawler->filter("a[title=Valider]")->link();

		$client->click($validateLInk);

		return $client;
	}

	/**
	 * Test l'accès via un lien dans la liste des observatiosn à valider
	 *
	 * L'accès doit être accepté
	 */
	public function testAccessLinkObservationToValidateByRolePro()
	{
		//on recréer une observation pour pouvoir la valider

		$this->setAddValidObservation();

		$client = $this->getAccessLinkObservationToValidateByRolePro();


		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}


	/**
	 * Test la validation d'une observation le formulaire de validation des observations
	 *
	 */
	public function testValidateFormObservationValidation()
	{
		$client = $this->getAccessLinkObservationToValidateByRolePro();
		$crawler = $client->getCrawler();

		$form = $crawler->selectButton("Valider l'observation")->form();

		$form['validate_observation[comment]'] = "Observation OK pour moi";

//		$crawler->selectButton('#validate_observation_valid')->

		$client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$textAlert = $crawler->filter('.alert')->text();
		$textToVerif = "Merci d'avoir validé l'observation, l'auteur a été averti !";
		$this->assertContains($textToVerif, $textAlert);
	}

	/**
	 * Test le refus d'une observation le formulaire de validation des observations
	 *
	 */
	public function testRefusedFormObservationValidation()
	{
		$this->setAddValidObservation();//On ajout une nouvelle observations à valider

		$client = $this->getAccessLinkObservationToValidateByRolePro();//On accède a la page de validation

		$crawler = $client->getCrawler();

		$form = $crawler->selectButton("Rejeter l'observation")->form();

		$form['validate_observation[comment]'] = "Observation non ok pour moi";

		$client->submit($form);

		$this->assertTrue($client->getResponse()->isRedirect());

		$crawler = $client->followRedirect();

		$textAlert = $crawler->filter('.alert')->text();
		$textToVerif = "L'observation a été rejetée, l'auteur a été averti !";
		$this->assertContains($textToVerif, $textAlert);
	}
}
