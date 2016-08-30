<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 14/08/16
 * Time: 15:59
 */

namespace AppBundle\Repository;


use AppBundle\Entity\locality\City;
use AppBundle\Entity\locality\Department;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Species;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserInterface;

class ObservationRepository extends EntityRepository
{

	public function getQueryByAuthor(UserInterface $user, $orders = array('o.datetimeObservation' => 'DESC'))
	{
		$query  = $this->createQueryBuilder('o')
			->where("o.author = :author")->setParameter('author', $user)
		;

		foreach ($orders as $field => $order){
			$query->addOrderBy($field, $order);
		}

		return $query;
	}

	public function getQueryByState($state, $orders = array('o.datetimeObservation' => 'ASC'))
	{
		$query  = $this->createQueryBuilder('o')
			->where("o.state = :state")->setParameter('state', $state)
			;

		foreach ($orders as $field => $order){
			$query->addOrderBy($field, $order);
		}

		return $query;
	}

	public function search(\DateTime $startDate, \DateTime $endDate, Department $department = null, City $city = null, Species $species = null, $orders = array('o.datetimeObservation' => 'ASC'))
    {
        $query = $this->createQueryBuilder("o")
	        ->where("o.state = :state")->setParameter('state', Observation::STATE_VALIDATED)
            ->andWhere("o.datetimeObservation >= :startDate")->setParameter('startDate', $startDate)
            ->andWhere("o.datetimeObservation <= :endDate")->setParameter('endDate', $endDate);

	    if(null !== $department && null === $city){
		    $query->join("o.city", "city")
		        ->andWhere("city.department = :department")
		            ->setParameter(":department", $department);
	    }

        if(null !== $city){
            $query->andWhere("o.city = :city")->setParameter('city', $city->getId());//besoin de prendre l'id car bug avec la session
        }

        if(null !== $species) {
            $query->andWhere("o.species = :species")->setParameter('species', $species);
        }

        foreach ($orders as $field => $order){
            $query->addOrderBy($field, $order);
        }

        return $query;
    }
}