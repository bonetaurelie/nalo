<?php

namespace OC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Saisie
 *
 * @ORM\Table(name="saisie")
 * @ORM\Entity(repositoryClass="OC\CoreBundle\Repository\SaisieRepository")
 */
class Saisie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="espece", type="string", length=255)
     */
    private $espece;

    /**
     * @var string
     *
     * @ORM\Column(name="especebis", type="string", length=255)
     */
    private $especebis;

    /**
     * @var int
     *
     * @ORM\Column(name="indiv", type="integer")
     */
    private $indiv;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Saisie
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set departement
     *
     * @param string $departement
     *
     * @return Saisie
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set commune
     *
     * @param string $commune
     *
     * @return Saisie
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return string
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Saisie
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set espece
     *
     * @param string $espece
     *
     * @return Saisie
     */
    public function setEspece($espece)
    {
        $this->espece = $espece;

        return $this;
    }

    /**
     * Get espece
     *
     * @return string
     */
    public function getEspece()
    {
        return $this->espece;
    }

    /**
     * Set especebis
     *
     * @param string $especebis
     *
     * @return Recherche
     */
    public function setEspecebis($especebis)
    {
        $this->especebis = $especebis;

        return $this;
    }

    /**
     * Get especebis
     *
     * @return string
     */
    public function getEspecebis()
    {
        return $this->especebis;
    }

    /**
     * Set indiv
     *
     * @param integer $indiv
     *
     * @return Saisie
     */
    public function setIndiv($indiv)
    {
        $this->indiv = $indiv;

        return $this;
    }

    /**
     * Get indiv
     *
     * @return int
     */
    public function getIndiv()
    {
        return $this->indiv;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Saisie
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}

