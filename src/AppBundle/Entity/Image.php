<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 17/08/16
 * Time: 09:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Image
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="nalo_image")
 * @Vich\Uploadable
 */
class Image
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
	 *
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $fileName;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $alt;


	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Observation", inversedBy="images")
	 * @ORM\JoinColumn(name="observation_id", referencedColumnName="id")
	 */
	protected $observation;

	/**
	 * @var File
	 * @Vich\UploadableField(mapping="observation_image", fileNameProperty="fileName")
	 * @Assert\Image(
	 *     minWidth = 600,
	 *     maxWidth = 1200,
	 *     minHeight = 600,
	 *     maxHeight = 1200,
	 *     maxSize = "600k",
	 *     mimeTypes = {"image/jpeg", "image/jpg"}
	 * )
	 */
	protected $file;

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
     * Set url
     *
     * @param string $fileName
     *
     * @return Image
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return Image
     */
    public function setObservation(\AppBundle\Entity\Observation $observation = null)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return \AppBundle\Entity\Observation
     */
    public function getObservation()
    {
        return $this->observation;
    }

	/**
	 * Set file
	 *
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
    public function setFile(File $file = null)
    {
		$this->file = $file;

	    return $this;
    }

	/**
	 * Get file
	 *
	 * @return mixed
	 */
    public function getFile()
    {
        return $this->file;
    }
}
