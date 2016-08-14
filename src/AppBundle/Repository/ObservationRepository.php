<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 14/08/16
 * Time: 15:59
 */

namespace AppBundle\Repository;


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
}