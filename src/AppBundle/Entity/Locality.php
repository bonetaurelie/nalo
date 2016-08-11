<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Locality
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocalityRepository")
 * @ORM\Table(name="nalo_locality")
 *
 */
class Locality
{

	/**
	 * @var integer
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Locality", mappedBy="parent")
	 */
	protected $children;

	/**
	 * @var integer
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locality", inversedBy="id")
	 * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
	 */
	protected $parent;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $adminName;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $postalCode;

	/**
	 * @var double
	 * @ORM\Column(type="decimal", scale=8, nullable=false)
	 */
	protected $longitude;

	/**
	 * @var double
	 * @ORM\Column(type="decimal", scale=8, nullable=false)
	 */
	protected $latitude;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", length=2, nullable=true)
	 */
	protected $accuracy;

	public function __construct()
	{
		$this->children = new ArrayCollection();
	}

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
     * Set adminName
     *
     * @param string $adminName
     *
     * @return Locality
     */
    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;

        return $this;
    }

    /**
     * Get adminName
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->adminName;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Locality
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Locality
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Locality
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set accuracy
     *
     * @param integer $accuracy
     *
     * @return Locality
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    /**
     * Get accuracy
     *
     * @return integer
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * Add child
     *
     * @param \appBundle\Entity\Locality $child
     *
     * @return Locality
     */
    public function addChild(Locality  $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \appBundle\Entity\Locality  $child
     */
    public function removeChild(Locality  $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \appBundle\Entity\Locality  $parent
     *
     * @return Locality
     */
    public function setParent(Locality $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \appBundle\Entity\Locality
     */
    public function getParent()
    {
        return $this->parent;
    }
}
