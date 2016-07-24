<?php

namespace OC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewMdp
 *
 * @ORM\Table(name="new_mdp")
 * @ORM\Entity(repositoryClass="OC\CoreBundle\Repository\NewMdpRepository")
 */
class NewMdp
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
     * @ORM\Column(name="mdp", type="string", length=255)
     */
    private $mdp;

    /**
     * @var string
     *
     * @ORM\Column(name="newmdp", type="string", length=255)
     */
    private $newmdp;


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
     * Set mdp
     *
     * @param string $mdp
     *
     * @return NewMdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set newmdp
     *
     * @param string $newmdp
     *
     * @return NewMdp
     */
    public function setNewmdp($newmdp)
    {
        $this->newmdp = $newmdp;

        return $this;
    }

    /**
     * Get newmdp
     *
     * @return string
     */
    public function getNewmdp()
    {
        return $this->newmdp;
    }
}

