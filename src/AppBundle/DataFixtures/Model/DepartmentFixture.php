<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/08/16
 * Time: 16:17
 */

namespace AppBundle\DataFixtures\Model;


use AppBundle\Entity\locality\Department;

class DepartmentFixture extends Department
{
	/**
	 * Set adminName
	 *
	 * @param string $adminName
	 *
	 * @return Department
	 */
	public function setAdminName($adminName)
	{
		$this->adminName = $adminName;

		return $this;
	}

	/**
	 * Add city
	 *
	 * @param \AppBundle\Entity\locality\City $city
	 *
	 * @return Department
	 */
	public function addCity(\AppBundle\Entity\locality\City $city)
	{
		$this->cities[] = $city;

		return $this;
	}

	/**
	 * Remove city
	 *
	 * @param \AppBundle\Entity\locality\City $city
	 */
	public function removeCity(\AppBundle\Entity\locality\City $city)
	{
		$this->cities->removeElement($city);
	}

	/**
	 * Set departmentCode
	 *
	 * @param string $departmentCode
	 *
	 * @return Department
	 */
	public function setDepartmentCode($departmentCode)
	{
		$this->departmentCode = $departmentCode;

		return $this;
	}
}