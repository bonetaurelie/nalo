<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use UserBundle\UserBundle;

/**
 * Class Observation
 * @package AppBundle\Entity
 *
 * Représente une observation
 *
 * @ORM\Entity()
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
	 */
	protected $locality;

	/**
	 * @var \UserBundle\Entity\User
	 * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
	 */
	protected $author;

	/**
	 * @var \AppBundle\Entity\Species
	 * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Species")
	 */
	protected $species;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", length=3)
	 */
	protected $nbIndividual;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $comment;

	/**
	 * @var interger
	 * @ORM\Column(type="integer", length=1)
	 */
	protected $state;

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
     * Set locality
     *
     * @param \AppBundle\Entity\locality\City $locality
     *
     * @return Observation
     */
    public function setLocality(\AppBundle\Entity\locality\City $locality = null)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return \AppBundle\Entity\locality\City
     */
    public function getLocality()
    {
        return $this->locality;
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
}
