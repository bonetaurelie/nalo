<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * Class Observation
 * @package AppBundle\Entity
 *
 * Représente une observation
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 * @ORM\table(name="nalo_observation")
 */
class Observation
{
	/* différents états possible de l'entité */

	//en attente quand il vient d'être émis
	const STATE_STANDBY     = 0;
	//si un professionnel est en train de regarder l'observation // a vérifier si c'est possible
	const STATE_IN_STUDY    = 1;

	const STATE_VALIDATED   = 2;
	const STATE_REFUSED     = 3;

	const DEFAULT_ITEMS_BY_PAGE = 10;

	const NUM_PICTURES_MAX  = 4;

	/**
	 * @var integer
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var \Datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $datetimeObservation;

	/**
	 * @var \AppBundle\Entity\locality\City
	 * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\locality\City")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $locality;

    /**
     * @var float
     * @ORM\Column(type="float", length=15, scale=10)
     */
    protected $longitude;

    /**
     * @var float
     * @ORM\Column(type="float", length=15, scale=10)
     */
    protected $latitude;

	/**
	 * @var \UserBundle\Entity\User
	 * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $author;

	/**
	 * @var \AppBundle\Entity\Species
	 * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Species")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $species;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", length=3)
	 * @Assert\NotBlank()
	 * @Assert\Length(min=1, max=3, minMessage="observations.form.error.minNbIndividual", maxMessage="observations.form.error.minNbIndividual")
	 */
	protected $nbIndividual;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $comment;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="observation", cascade={"persist", "remove"}, orphanRemoval=true)
	 */
	protected $images;

	/**
	 * @var interger
	 * @ORM\Column(type="integer", length=1)
	 * @Assert\NotBlank()
	 */
	protected $state;

	public function __construct()
	{
		$this->state = self::STATE_STANDBY;//par défaut l'état de l'observation est "en attente"

		$this->datetimeObservation = new \DateTime();

		$this->nbIndividual = 1;

		$this->images = new ArrayCollection();
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
     * Set nbIndividual
     *
     * @param integer $nbIndividual
     *
     * @return Observation
     */
    public function setNbIndividual($nbIndividual)
    {
        $this->nbIndividual = $nbIndividual;

        return $this;
    }

    /**
     * Get nbIndividual
     *
     * @return integer
     */
    public function getNbIndividual()
    {
        return $this->nbIndividual;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Observation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Observation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\locality\City $locality
     *
     * @return Observation
     */
    public function setCity(\AppBundle\Entity\locality\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\locality\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set author
     *
     * @param \UserBundle\Entity\User $author
     *
     * @return Observation
     */
    public function setAuthor(\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set species
     *
     * @param \AppBundle\Entity\species $species
     *
     * @return Observation
     */
    public function setSpecies(\AppBundle\Entity\species $species = null)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return \AppBundle\Entity\species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set datetimeObservation
     *
     * @param \DateTime $datetimeObservation
     *
     * @return Observation
     */
    public function setDatetimeObservation($datetimeObservation)
    {
        $this->datetimeObservation = $datetimeObservation;

        return $this;
    }

    /**
     * Get datetimeObservation
     *
     * @return \DateTime
     */
    public function getDatetimeObservation()
    {
        return $this->datetimeObservation;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Observation
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
	    $image->setObservation($this);

    	$this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
	    $image->setObservation(null);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set locality
     *
     * @param string
     *
     * @return Observation
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Observation
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
     * @return Observation
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
}
