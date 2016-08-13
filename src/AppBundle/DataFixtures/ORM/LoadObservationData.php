<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 13/08/16
 * Time: 15:57
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Observation;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadObservationData implements FixtureInterface, OrderedFixtureInterface
{

	private $manager;

	private function getFakeObservation()
	{
		return (object) array(
				'locality' => $this->manager->getRepository('AppBundle:locality\City')->findOneBy(array('adminName'=> 'Le Thor')),
				'author'   => $this->manager->getRepository('UserBundle:User')->findOneBy(array('email'=> 'test.amateur@test.fr')),
				'species'   => $this->manager->getRepository('AppBundle:Species')->findOneBy(array('frenchName' => 'Aigle royal')),
				'datetimeObservation'   => new \DateTime(),
				'nbIndividual'   => rand(1,10),
				'comment'   => "commantaire ".rand(1,10),
				'state'   => rand(0,3)
			);
	}

	public function getFakeObservationsList($nb){
		$fakeObservations = [];

		for ($i=0;$i<=$nb;$i++){
			$fakeObservations[] = $this->getFakeObservation();
		}

		return $fakeObservations;
	}

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$this->manager = $manager;
		$fakeObservations = $this->getFakeObservationsList(20);

		foreach ($fakeObservations as $fakeObservation){
			$observation = new Observation();
			$observation
				->setLocality($fakeObservation->locality)
				->setAuthor($fakeObservation->author)
				->setSpecies($fakeObservation->species)
				->setDatetimeObservation($fakeObservation->datetimeObservation)
				->setNbIndividual($fakeObservation->nbIndividual)
				->setComment($fakeObservation->comment)
				->setState($fakeObservation->state)
			;

			$manager->persist($observation);
		}

		$manager->flush();
	}


	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 3;
	}
}