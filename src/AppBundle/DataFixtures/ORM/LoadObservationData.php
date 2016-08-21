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
use Doctrine\ORM\EntityManager;

class LoadObservationData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var EntityManager
     */
	private $manager;

	private function getFakeObservation()
	{
		$towns = ['Le Thor', 'Saint-Martin-du-Frêne', 'Biarritz'];
		$species = ['Canard d\'Eaton', 'Bernache à ventre pâle', 'Martinet de Cayenne ', 'Aigle royal'];
		$dateStr = '2016-08-'.str_pad(mt_rand(1,31),2,'0', STR_PAD_LEFT).' '.str_pad(mt_rand(0,23),2,'0', STR_PAD_LEFT).':'.str_pad(mt_rand(0,59),2,'0', STR_PAD_LEFT);

		$state = mt_rand(0,3);
		$state = $state == 1?0:$state;//ignore pour le moment le status "En cours"

        $city = $this->manager->getRepository('AppBundle:locality\City')->findOneBy(array('adminName'=> $towns[mt_rand(0,2)]));

		return (object) array(
				'city' => $city,
                'locality' => 'lieu dit  '.mt_rand(1,10),
                'longitude' => $city->getLongitude(),
                'latitude' => $city->getLatitude(),
				'author'   => $this->manager->getRepository('UserBundle:User')->findOneBy(array('email'=> 'test.amateur@test.fr')),
				'species'   => $this->manager->getRepository('AppBundle:Species')->findOneBy(array('frenchName' => $species[mt_rand(0,3)])),
				'datetimeObservation'   => \DateTime::createFromFormat('Y-m-d H:i', $dateStr),
				'nbIndividual'   => mt_rand(1,10),
				'comment'   => "commentaire ".mt_rand(1,10),
				'state'   => $state
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
				->setCity($fakeObservation->city)
				->setLongitude($fakeObservation->longitude)
				->setLatitude($fakeObservation->latitude)
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
		return 4;
	}
}