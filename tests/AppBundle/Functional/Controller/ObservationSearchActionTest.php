<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 29/08/16
 * Time: 21:27
 */

namespace Tests\AppBundle\Functional\Controller;


use AppBundle\Entity\locality\City;
use AppBundle\Entity\locality\Department;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Species;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UserBundle\Entity\User;

class ObservationSearcActionTest extends WebTestCase
{

	const SEARCH_ROUTE          = '/recherche';

	/**
	 * @var EntityManager
	 */
	private $_em;

	/**
	 * @var Department
	 */
	private $department;

	/**
	 * @var City
	 */
	private $city;

	/**
	 * @var Species
	 */
	private $species;

	/**
	 * @var User
	 */
	private $user;

	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->_em->beginTransaction();

		$this->department   = $this->getFakeDepartment();
		$this->city         = $this->getFakeCity();
		$this->species      = $this->getFakeSpecies();
		$this->user         = $this->getFakeUser();
	}

	/**
	 * Rollback changes.
	 */
	public function tearDown()
	{
		$this->_em->rollback();
	}

	public function addObservation()
	{

		$observation = new Observation();
		$observation
			->setNbIndividual(2)
			->setState(Observation::STATE_VALIDATED)
			->setAuthor($this->user)
			->setCity($this->city)
			->setLocality('Domaine du verger')
			->setComment("test recherche")
			->setLongitude($this->city->getLongitude())
			->setLatitude($this->city->getLatitude())
			->setSpecies($this->species)
		;

		$this->_em->persist($observation);
		$this->_em->flush();

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
		return $this->_em->getRepository("AppBundle:Species")->findOneByFrenchName("Aigle royal");
	}

	public function getFakeUser()
	{
		return $this->_em->getRepository("UserBundle:User")->findOneByEmail("test.professionnel@test.fr");
	}

	/**
	 * Test if error when the form fields are empty
	 */
	public function testFormAllEmpty()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=search]')->form();

		$form->disableValidation();

		$form['search[startDate]'] = '';
		$form['search[endDate]'] = '';

		$form['search[department]'] = '';
		$form['search[city]'] = '';
		$form['search[species]'] = '';

		$crawler = $client->submit($form);

		$statDateStr = $crawler->filter("#search_startDate")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("Cette valeur ne doit pas être vide.", $statDateStr);

		$endDateStr = $crawler->filter("#search_endDate")->parents('div')->parents('div > .help-block ul li')->text();
		$this->assertContains("Cette valeur ne doit pas être vide.", $endDateStr);
	}

	/**
	 * test avec juste la période séléctionné
	 */
	public function testFormJustPeriodSelected()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=search]')->form();

		$form->disableValidation();

		$form['search[startDate]'] = '01/08/2016';
		$form['search[endDate]'] = '31/08/2016';

		$form['search[department]'] = '';
		$form['search[city]'] = '';
		$form['search[species]'] = '';

		$crawler = $client->submit($form);

		$nbResultats =  $crawler->filter("#observations-list > li")->count();

		$this->assertGreaterThan(0, $nbResultats);
	}

	/**
	 * Test if error when the form fields are empty
	 */
	public function testFormSetDepartment()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=search]')->form();

		$form->disableValidation();

		$form['search[startDate]'] = '01/08/2016';
		$form['search[endDate]'] = '31/08/2016';

		$form['search[department]'] = $this->department->getId();
		$form['search[city]'] = '';
		$form['search[species]'] = '';

		$crawler = $client->submit($form);

		$nbResultats =  $crawler->filter("#observations-list > li")->count();

		$this->assertGreaterThan(0, $nbResultats);
	}


	/**
	 * Test if error when the form fields are empty
	 */
	public function testFormSetDepartmentAndCity()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=search]')->form();

		$form->disableValidation();

		$form['search[startDate]'] = '01/08/2016';
		$form['search[endDate]'] = '31/08/2016';

		$form['search[department]'] = $this->department->getId();
		$form['search[city]']       = $this->city->getId();
		$form['search[species]'] = '';

		$crawler = $client->submit($form);

		$nbResultats =  $crawler->filter("#observations-list > li")->count();

		$this->assertGreaterThan(0, $nbResultats);
	}

	public function testFormSetAllFields()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=search]')->form();

		$form->disableValidation();

		$form['search[startDate]'] = '01/08/2016';
		$form['search[endDate]'] = '31/08/2016';

		$form['search[department]'] = $this->department->getId();
		$form['search[city]']       = $this->city->getId();
		$form['search[species]']    = $this->species->getId();

		$crawler = $client->submit($form);

		$nbResultats =  $crawler->filter("#observations-list > li")->count();

		$this->assertGreaterThan(0, $nbResultats);
	}
}