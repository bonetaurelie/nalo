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
     * Get adminName
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->adminName;
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
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
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
