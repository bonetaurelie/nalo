<?php
namespace AppBundle\Entity\locality;

use AppBundle\Entity\ObservableLocalityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class City
 * @package AppBundle\Entity\locality
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 * @ORM\Table(name="nalo_city")
 */
class City
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
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $postalCode;

    /**
     * @var double
     * @ORM\Column(type="decimal", length=10, scale=7, nullable=false)
     */
    protected $longitude;

    /**
     * @var double
     * @ORM\Column(type="decimal", length=10, scale=7, nullable=false)
     */
    protected $latitude;


    /**
     * @var Department
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\locality\Department", inversedBy="cities")
     */
    protected $department;

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
     * @return City
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
     * @return City
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
     * @return City
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
     * @return City
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
     * Set department
     *
     * @param \AppBundle\Entity\locality\Department $department
     *
     * @return City
     */
    public function setDepartment(\AppBundle\Entity\locality\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \AppBundle\Entity\locality\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }
}
