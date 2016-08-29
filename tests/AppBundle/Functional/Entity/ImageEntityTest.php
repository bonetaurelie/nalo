<?php

namespace tests\AppBundle\Functional\Entity;


use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageEntityTest extends WebTestCase
{
	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var Image
	 */
	private $image;

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	/**
	 * @var string
	 */
	private $rootDir;

	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->em->beginTransaction();

		$this->rootDir = $kernel->getRootDir();


		$this->image = new Image();

		$this->validator = $kernel->getContainer()->get('validator.builder')->getValidator();
	}

	/**
	 * Annulation transaction base
	 */
	public function tearDown()
	{
		$this->em->rollback();
	}


	public function getFakeBadPictureTooSmall()
	{
		return (object) [
			'alt' => 'bad-picture-too-small',
			'picture' => new UploadedFile(
				$this->rootDir.'/Resources/dataSources/bad-picture-too-small.jpg',
				'bad-picture-too-small.jpg',
				'image/jpg',
				14921
			)
		];
	}

	public function getFakeBadPictureTooBig()
	{
		return (object) [
			'alt' => 'bad-picture-too-big',
			'picture' => new UploadedFile(
				$this->rootDir.'/Resources/dataSources/bad-picture-too-big.jpg',
				'bad-picture-too-big.jpg',
				'image/jpg',
				5087925
			)
		];
	}

	public function getFakeBadPictureBadFormat()
	{
		return (object) [
			'alt' => 'bad-picture-bad-format',
			'picture' => new UploadedFile(
				$this->rootDir.'/Resources/dataSources/bad-picture-bad-format.png'
//				,
//				'bad-picture-bad-format.png',
//				'image/png',
//				67734
			)
		];
	}

	public function getFakeGoodPicture()
	{
		$path = $this->rootDir.'/Resources/dataSources/good-picture.jpg';


		return (object) [
			'alt' => 'Good picture',
			'picture' => new UploadedFile(
				$path,
				'good-picture.jpg',
				'image/jpg',
				filesize($path)
			)
		];
	}


	public function getFakeObservation()
	{
		return $this->em->getRepository('AppBundle:Observation')->find(1);
	}

	public function testBadDataForImageTooSmall()
	{
		$image = $this->getFakeBadPictureTooSmall();

		$this->image->setAlt($image->alt);
		$this->image->setFile($image->picture);
		$this->image->setObservation($this->getFakeObservation());

		$errors = $this->validator->validate($this->image);

//		var_dump($errors);
		$this->assertEquals(1, count($errors));
	}

	public function testGoodDataForImage()
	{
		$image = $this->getFakeGoodPicture();

		$this->image->setAlt($image->alt);
		$this->image->setFile($image->picture);
		$this->image->setObservation($this->getFakeObservation());

		$errors = $this->validator->validate($this->image);

		$this->assertEquals(0, count($errors));
	}
}