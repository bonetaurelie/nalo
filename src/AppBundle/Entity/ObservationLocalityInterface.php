<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 16/08/16
 * Time: 15:15
 */

namespace AppBundle\Entity;


interface ObservationLocalityInterface
{
	/**
	 * Retourne la Latitude de la localisation
	 * @return float
	 */
	public function getLatitude();

	/**
	 * Retourne la longitude de la localisation
	 * @return float
	 */
	public function getLongitude();

	/**
	 * Retourne le nom de la localisation
	 * @return string
	 */
	public function getName();
}