<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Class species
 * @package AppBundle\Entity
 * @ORM\Table(name="nalo_species")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesRepository")
 */
class Species
{
	/**
	 * @var integer
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $frenchName;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $latinName;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $author;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get frenchName
     *
     * @return string
     */
    public function getFrenchName()
    {
        return $this->frenchName;
    }

	/**
	 * Get latinName
	 *
	 * @return string
	 */
	public function getLatinName()
	{
		return $this->latinName;
	}

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

}
