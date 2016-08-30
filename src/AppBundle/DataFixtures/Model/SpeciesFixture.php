<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 30/08/16
 * Time: 16:23
 */

namespace AppBundle\DataFixtures\Model;


use AppBundle\Entity\Species;

class SpeciesFixture extends Species
{
	/**
	 * Set frenchName
	 *
	 * @param string $frenchName
	 *
	 * @return Species
	 */
	public function setFrenchName($frenchName)
	{
		$this->frenchName = $frenchName;

		return $this;
	}

	/**
	 * Set latinName
	 *
	 * @param string $latinName
	 *
	 * @return Species
	 */
	public function setLatinName($latinName)
	{
		$this->latinName = $latinName;

		return $this;
	}

	/**
	 * Set author
	 *
	 * @param string $author
	 *
	 * @return Species
	 */
	public function setAuthor($author)
	{
		$this->author = $author;

		return $this;
	}
}