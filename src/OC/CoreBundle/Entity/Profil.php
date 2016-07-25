<?php

namespace OC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profil
 *
 * @ORM\Table(name="profil")
 * @ORM\Entity(repositoryClass="OC\CoreBundle\Repository\ProfilRepository")
 */
class Profil
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
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="emailactu", type="string", length=255)
     */
    private $emailactu;

    /**
     * @var string
     *
     * @ORM\Column(name="emailnew", type="string", length=255)
     */
    private $emailnew;

    /**
     * @var string
     *
     * @ORM\Column(name="mdpnew", type="string", length=255)
     */
    private $mdpnew;

    /**
     * @var string
     *
     * @ORM\Column(name="confmdp", type="string", length=255)
     */
    private $confmdp;


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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Profil
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Profil
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set emailactu
     *
     * @param string $emailactu
     *
     * @return Profil
     */
    public function setEmailactu($emailactu)
    {
        $this->emailactu = $emailactu;

        return $this;
    }

    /**
     * Get emailactu
     *
     * @return string
     */
    public function getEmailactu()
    {
        return $this->emailactu;
    }

    /**
     * Set emailnew
     *
     * @param string $emailnew
     *
     * @return Profil
     */
    public function setEmailnew($emailnew)
    {
        $this->emailnew = $emailnew;

        return $this;
    }

    /**
     * Get emailnew
     *
     * @return string
     */
    public function getEmailnew()
    {
        return $this->emailnew;
    }

    /**
     * Set mdpnew
     *
     * @param string $mdpnew
     *
     * @return Profil
     */
    public function setMdpnew($mdpnew)
    {
        $this->mdpnew = $mdpnew;

        return $this;
    }

    /**
     * Get mdpnew
     *
     * @return string
     */
    public function getMdpnew()
    {
        return $this->mdpnew;
    }

    /**
     * Set confmdp
     *
     * @param string $confmdp
     *
     * @return Profil
     */
    public function setConfmdp($confmdp)
    {
        $this->confmdp = $confmdp;

        return $this;
    }

    /**
     * Get confmdp
     *
     * @return string
     */
    public function getConfmdp()
    {
        return $this->confmdp;
    }
}

