<?php

namespace tests\AppBundle\Functional\Entity;

use AppBundle\Entity\Image;
use AppBundle\Entity\Observation;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\Validator\Validator\ValidatorInterface;


class ObservationEntityTest extends WebTestCase
{
	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var Observation
	 */
	private $observation;

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->em->beginTransaction();

		$this->observation = new Observation();

		$this->validator = $kernel->getContainer()->get('validator.builder')->getValidator();
	}

	protected function getFakeAuthor()
	{
		return $this->em->getRepository('UserBundle:User')->find(1);
	}

	protected function getFakeCity()
	{
		return $this->em->getRepository('AppBundle:locality\City')->findOneByAdminName("Le Thor");
	}

	protected function getFakeSpecies()
	{
		return $this->em->getRepository('AppBundle:Species')->findOneByFrenchName("Martinet de Cayenne");
	}

	protected function getFakeImage()
	{
		$image = new Image();
		$image->setAlt('test')
			->setFileName('test.jpg')
		;
		return $image;
	}

	public function getGoodFakeObservation()
	{
		$author = $this->getFakeAuthor();
		$city = $this->getFakeCity();
		$species = $this->getFakeSpecies();

		return (object)[
			'author' => $author,
			'city' => $city,
			'locality' => 'Centre ville',
			'longitude' => $city->getLongitude(),
			'latitude' => $city->getLatitude(),
			'nbIndividual' => 3,
			'species'   => $species,
			'state'     => 0,
			'commnent' => 'test commentaired sfdsefr esfsdf sdsdfds fdsf sdfsdf sdf sdf sdfsd fsdffsd fsdfs dfsdfsdfsd fsdf'
		];
	}

	public function getBadFakeObservation()
	{
		return (object)[
			'author' => null,
			'city' => null,
			'locality' => null,
			'longitude' => null,
			'latitude' => null,
			'nbIndividual' => 0,
			'species'   => null,
			'state'     => null,
			'commnent' => ''
		];
	}

	/**
	 * Annulation transaction base
	 */
	public function tearDown()
	{
		$this->em->rollback();
	}

	/**
	 * Doit retourné un DateTime du jour
	 */
	public function testGetDatetimeObservationBeforeUse()
	{
		$this->assertEquals(new \DateTime(), $this->observation->getDatetimeObservation());
	}

	public function testSetBadData()
	{
		$fakeObservation = $this->getBadFakeObservation();

		$this->observation->setAuthor($fakeObservation->author);
		$this->observation->setCity($fakeObservation->city);
		$this->observation->setLocality($fakeObservation->locality);
		$this->observation->setLongitude($fakeObservation->longitude);
		$this->observation->setLatitude($fakeObservation->latitude);
		$this->observation->setNbIndividual($fakeObservation->nbIndividual);
		$this->observation->setSpecies($fakeObservation->species);
		$this->observation->setComment($fakeObservation->commnent);

		$errors = $this->validator->validate($this->observation);


		$this->assertEquals(1, count($errors));
	}

	public function testGoodData()
	{
		$fakeObservation = $this->getGoodFakeObservation();

		$this->observation->setAuthor($fakeObservation->author);
		$this->observation->setCity($fakeObservation->city);
		$this->observation->setLocality($fakeObservation->locality);
		$this->observation->setLongitude($fakeObservation->longitude);
		$this->observation->setLatitude($fakeObservation->latitude);
		$this->observation->setNbIndividual($fakeObservation->nbIndividual);
		$this->observation->setSpecies($fakeObservation->species);
		$this->observation->setState($fakeObservation->state);
		$this->observation->setComment($fakeObservation->commnent);
		$this->observation->addImage($this->getFakeImage());


		$errors = $this->validator->validate($this->observation);


		$this->assertEquals(0, count($errors));
	}

	public function testRemoveImage()
	{
		$this->observation->removeImage($this->getFakeImage());
	}
}