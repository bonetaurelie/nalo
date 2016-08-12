<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\locality\Department;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \League\Csv\Reader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLocalityDepartmentData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

	const DEPT_NAME_COL_NUM         = 1;
    const CODE_DEPT_COL_NUM         = 0;
	const DATA_FILE_PATH            = 'Resources/dataSources/departement.csv';

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
        $this->loadDataDepartment();
	}

    /**
     * Charge le csv des départements
     */
	public function loadCsvData(){
        $root =  $this->container->getParameter('kernel.root_dir').'/';

        $csv =Reader::createFromPath( $root . self::DATA_FILE_PATH);
        $csv->setDelimiter(';');
        $this->csvData = $csv->fetchAll();
	}

    /**
     * enregistre les départements
     */
	public function loadDataDepartment()
	{
		foreach($this->csvData as $row){
			$adminName  = $row[self::DEPT_NAME_COL_NUM];
			$code       = $row[self::CODE_DEPT_COL_NUM];

			//on filtre pour éviter les champs vide
			if( empty($adminName)){
				continue;
			}

            $entity = new Department();
            $entity->setAdminName($adminName)->setDepartmentCode($code);

			$this->manager->persist($entity);
		}

		$this->manager->flush();
	}

    public function getOrder()
    {
       return 2;
    }
}