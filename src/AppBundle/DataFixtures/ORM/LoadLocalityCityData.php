<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\locality\City;
use AppBundle\Entity\locality\Department;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \League\Csv\Reader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLocalityCityData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    const DEPT_CODE_COL_NUM         = 0;
    const ADMIN_NAME_COL_NUM        = 1;
    const POSTAL_CODE_COL_NUM       = 2;
    const LONGITUDE_COL_NUM         = 3;
    const LATITUDE_COL_NUM          = 4;

    const DATA_FILE_PATH            = 'Resources/dataSources/city.csv';

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

        $this->getListDepartments();
    }

    /**
     * Charge le csv des départements
     */
    public function loadCsvDataFilterByCodeDeparment($deptCode)
    {
        $root =  $this->container->getParameter('kernel.root_dir').'/';

        $csv =Reader::createFromPath( $root . self::DATA_FILE_PATH);
        $csv->setDelimiter(';');

        $csv->addFilter(function($row) use($deptCode){
            return ($row[self::DEPT_CODE_COL_NUM] == $deptCode);
        });

        $this->csvData = $csv->fetchAll();
    }

    public function getListDepartments(){
        $departments = $this->manager->getRepository("AppBundle:locality\\Department")->findAll();

        foreach ($departments as $department){
            $this->loadCsvDataFilterByCodeDeparment($department->getDepartmentCode());
            $this->loadCityDataByDepartment($department);
        }
    }

    /**
     * enregistre les villes
     */
    public function loadCityDataByDepartment(Department $department)
    {
        foreach($this->csvData as $row)
        {
            $dataTreated = $this->treatmentData($row);

            if(empty($dataTreated->adminName)){
                continue;
            }

            $entity = new City();
            $entity
                ->setAdminName($dataTreated->adminName)
                ->setPostalCode($dataTreated->postalCode)
                ->setLongitude($dataTreated->longitude)
                ->setLatitude($dataTreated->latitude)
                ->setDepartment($department)
            ;

            $this->manager->persist($entity);
        }

        $this->manager->flush();
    }

    public function treatmentData($row)
    {
        $adminName  = $row[self::ADMIN_NAME_COL_NUM];
        $code       = str_pad( substr($row[self::POSTAL_CODE_COL_NUM],0,5), 5, "0", STR_PAD_LEFT);
        $longitude  = $row[self::LONGITUDE_COL_NUM]=== ''?0:$row[self::LONGITUDE_COL_NUM];
        $latitude   =$row[self::LATITUDE_COL_NUM]=== ''?0:$row[self::LATITUDE_COL_NUM];

        return (object) array(
            'adminName' =>$adminName,
            'postalCode' => $code,
            'longitude' => $longitude,
            'latitude' => $latitude
        );
    }

    public function getOrder()
    {
        return 2;
    }
}