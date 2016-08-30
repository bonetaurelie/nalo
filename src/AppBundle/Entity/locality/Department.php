<?php
namespace AppBundle\Entity\locality;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Department
 * @package AppBundle\Entity\locality
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepartmentRepository")
 * @ORM\Table(name="nalo_department")
 */
class Department
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $adminName;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     */
    protected $departmentCode;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\locality\City", mappedBy="department")
     */
    protected $cities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get adminName
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->adminName;
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }


    /**
     * Get departmentCode
     *
     * @return string
     */
    public function getDepartmentCode()
    {
        return $this->departmentCode;
    }
}
