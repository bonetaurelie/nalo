<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/08/16
 * Time: 16:11
 */

namespace AppBundle\DataFixtures\Model;


use AppBundle\Entity\locality\City;

class CityFixture extends City
{
	/**
	 * Set adminName
	 *
	 * @param string $adminName
	 *
	 * @return City
	 */
	public function setAdminName($adminName)
	{
		$this->adminName = $adminName;

		return $this;
	}


	/**
	 * Set postalCode
	 *
	 * @param string $postalCode
	 *
	 * @return City
	 */
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;

		return $this;
	}

	/**
	 * Set longitude
	 *
	 * @param string $longitude
	 *
	 * @return City
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}

	/**
	 * Set department
	 *
	 * @param \AppBundle\Entity\locality\Department $department
	 *
	 * @return City
	 */
	public function setDepartment(\AppBundle\Entity\locality\Department $department = null)
	{
		$this->department = $department;

		return $this;
	}
}