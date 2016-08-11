<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \League\Csv\Reader;
use AppBundle\Entity\Locality;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLocalityData implements FixtureInterface, ContainerAwareInterface
{
	const REGION_NAME_COL_NUM       = 3;
	const DEPT_NAME_COL_NUM         = 5;
	const CITY_NAME_COL_NUM         = 2;
	const STREET_NAME_COL_NUM       = 7;

	const CODE_POSTAL_CITY_COL_NUM  = 1;
	const CODE_POSTAL_DEPT_COL_NUM  = 6;

	const LATITUDE_COL_NUM          = 9;
	const LONGITUDE_COL_NUM         = 10;
	const ACCURACY_COL_NUM          = 11;

	const DATA_FILE_PATH            = 'Resources/dataSources/locality.csv';

	/**
	 * The dependency injection container.
	 *
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/**
	 * @var array
	 */
	protected $csvData;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}


	public function load(ObjectManager $manager)
	{
		$this->manager = $manager;

		$this->loadCsvData();

		$this->loadDataRegion();
		$this->loadDataDepartment();
		$this->loadDataCity();
	}

	public function loadCsvData(){
		$root =  $this->container->getParameter('kernel.root_dir').'/';

		$csv =Reader::createFromPath( $root . self::DATA_FILE_PATH);
		$csv->setDelimiter(';');

		$this->csvData = $csv->setOffset(1)->fetchAll();

	}

	public function loadDataRegion()
	{
        $alreadyAdd = [];

		foreach($this->csvData as $row){
			$adminName = $row[self::REGION_NAME_COL_NUM];
            //on filtre pour éviter les doublons et les champs vide
		    if(in_array($adminName, $alreadyAdd) || empty($adminName)){
		        continue;
            }
            //on ajoute le nom dans le tableau qui sert de filtre
            array_push($alreadyAdd, $adminName);

			$locality = $this->createEntity(
				$adminName,
				null,
				$row[self::LATITUDE_COL_NUM],
				$row[self::LONGITUDE_COL_NUM],
				$row[self::ACCURACY_COL_NUM]
			);

			$this->manager->persist($locality);
		}

		$this->manager->flush();
	}

	public function loadDataDepartment()
	{
		$alreadyAdd = [];
		$regions = [];

		foreach($this->csvData as $row){
			$adminName  = $row[self::DEPT_NAME_COL_NUM];
			$postalCode = $row[self::CODE_POSTAL_DEPT_COL_NUM];
			$regionName = $row[self::REGION_NAME_COL_NUM];

			//on filtre pour éviter les doublons et les champs vide
			if(in_array($adminName, $alreadyAdd) || empty($adminName)){
				continue;
			}

			//on ajoute le nom dans le tableau qui sert de filtre
			array_push($alreadyAdd, $adminName);

			if(!isset($regions[$regionName])){
				$region = $this->getInfoLocalityByAdminName($regionName);
				$regions[$regionName] = $region;
			}
			else{
				$region = $regions[$regionName];
			}

			$locality = $this->createEntity(
				$adminName,
				$postalCode,
				$row[self::LATITUDE_COL_NUM],
				$row[self::LONGITUDE_COL_NUM],
				$row[self::ACCURACY_COL_NUM],
				$region
			);

			$this->manager->persist($locality);
		}

		$this->manager->flush();
	}

	public function loadDataCity()
	{
		$alreadyAdd = [];
		$parents = [];

		foreach($this->csvData as $row){
			$adminName  = $row[self::CITY_NAME_COL_NUM];
			$postalCode = $row[self::CODE_POSTAL_CITY_COL_NUM];
			$parentName = $row[self::DEPT_NAME_COL_NUM];

			//on filtre pour éviter les doublons et les champs vide
			if(in_array($adminName, $alreadyAdd) || empty($adminName)){
				continue;
			}
			//on ajoute le nom dans le tableau qui sert de filtre
			$alreadyAdd[] = $adminName;

			if(!isset($parents[$parentName])){
				$parents = $this->getInfoLocalityByAdminName($parentName);
				$parents[$parentName] = $parents;
			}
			else{
				$parent = $parents[$parentName];
			}

			$locality = $this->createEntity(
				$adminName,
				$postalCode,
				$row[self::LATITUDE_COL_NUM],
				$row[self::LONGITUDE_COL_NUM],
				$row[self::ACCURACY_COL_NUM],
				$parent
			);

			$this->manager->persist($locality);
		}

		$this->manager->flush();
	}


	public function getInfoLocalityByAdminName($adminName){
		$repo = $this->manager->getRepository("AppBundle:Locality");

		return $repo->findOneByAdminName($adminName);
	}

	public function createEntity($adminName, $postalCode, $latitude, $longitude, $accuracy, $parent = null)
	{
		$locality = new Locality();
		$locality->setAdminName($adminName)
			->setPostalCode($postalCode)
			->setLatitude($latitude)
			->setLongitude($longitude)
			->setAccuracy($accuracy)
			->setParent($parent)
		;

		return $locality;
	}
}