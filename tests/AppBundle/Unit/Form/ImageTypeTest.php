<?php

namespace Tests\AppBundle\Unit\Form;


use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 *
 * Jeux de test pour tester le formulaire d'image
 *
 * Class ImageTypeTest
 *
 * @package Tests\AppBundle\Unit\Form
 */
class ImageTypeTest extends TypeTestCase
{
//	protected static $kernel;
//
//
//	/**
//	 * @var EntityManager
//	 */
//	private $_em;
//
//	/**
//	 * @var string
//	 */
//	private $rootDir;
//
//
//	protected function setUp()
//	{
//		self::$kernel = new \AppKernel('test', true);
//
//		// mock any dependencies
//		$this->_em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
//
//		$this->rootDir = self::$kernel->getRootDir();
//
//		parent::setUp();
//	}
//
//	/**
//	 * Rollback changes.
//	 */
//	public function tearDown()
//	{
//		$this->_em->rollback();
//	}
//
//	public function getFakeBadPictureTooSmall()
//	{
//		return (object) [
//			'alt' => 'bad-picture-too-small',
//			'picture' => new UploadedFile(
//				$this->rootDir.'/Resources/dataSources/bad-picture-too-small.jpg',
//				'bad-picture-too-small.jpg',
//				'image/jpg',
//				123
//			)
//		];
//	}
//
//	public function getFakeBadPictureTooBig()
//	{
//		return (object) [
//			'alt' => 'bad-picture-too-big',
//			'picture' => new UploadedFile(
//				$this->rootDir.'/Resources/dataSources/bad-picture-too-big.jpg',
//				'bad-picture-too-big.jpg',
//				'image/jpg',
//				123
//			)
//		];
//	}
//
//	public function getFakeBadPictureBadFormat()
//	{
//		return (object) [
//			'alt' => 'bad-picture-bad-format',
//			'picture' => new UploadedFile(
//				$this->rootDir.'/Resources/dataSources/bad-picture-bad-format.png',
//				'bad-picture-bad-format.png',
//				'image/png',
//				123
//			)
//		];
//	}
//
//	public function getFakeGoodPicture()
//	{
//
//
//		return (object) [
//			'alt' => 'Good picture',
//			'picture' => new UploadedFile(
//				$this->rootDir.'/Resources/dataSources/good-picture.jpg',
//				'good-pciture.jpg',
//				'image/jpg',
//				123
//			)
//		];
//	}
//
//
//	public function testSubmitValidData()
//	{
//		$image = $this->getFakeGoodPicture();
//
//		$formData = array(
//			'alt' => $image->alt,
//			'file' => $image->picture,
//		);
//
//		$form = $this->factory->create(ImageType::class);
//
////		$object = Image::fromArray($formData);
//
//		// submit the data to the form directly
//		$form->submit($formData);
//
//		$this->assertTrue($form->isSynchronized());
////		$this->assertEquals($object, $form->getData());
//
////		$view = $form->createView();
////		$children = $view->children;
////
////		foreach (array_keys($formData) as $key) {
////			$this->assertArrayHasKey($key, $children);
////		}
//	}
}