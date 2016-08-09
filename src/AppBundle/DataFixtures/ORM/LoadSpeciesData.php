<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \League\Csv\Reader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Species;

class LoadSpeciesData implements FixtureInterface, ContainerAwareInterface
{
    const LB_NOM_VER_COLUMN_NUM = 13;
    const LB_NOM_COLUMN_NUM = 9;
    const LB_AUTEUR_COLUMN_NUM = 10;

	/**
	 * The dependency injection container.
	 *
	 * @var ContainerInterface
	 */
	protected $container;


	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}


	public function load(ObjectManager $manager)
	{
		$root =  $this->container->getParameter('kernel.root_dir').'/';

		$csv =Reader::createFromPath( $root . 'Resources/dataSources/TAXREF_utf8.csv');
		$csv->setDelimiter(';');

    	$res = $csv->setOffset(1)->fetchAll();//->setLimit(25)

        $alreadyAdd = [];

		foreach($res as $row){
		    //on récupère les colonnes qu'on a besoin dans les champs qui corresponds
		    $frenchName = $row[self::LB_NOM_VER_COLUMN_NUM];
		    $latinName  = $row[self::LB_NOM_COLUMN_NUM];
		    $author  = $row[self::LB_AUTEUR_COLUMN_NUM];

            //on filtre pour éviter les doublons et les champs vide
		    if(in_array($frenchName,$alreadyAdd) || empty($frenchName)){
		        continue;
            }
            //on ajoute le nom dans le tableau qui sert de filtre
            array_push($alreadyAdd,$frenchName);

            //et enfin on ajoute l'espèce dans l'application
			$species = new Species();
			$species->setFrenchName($frenchName);
			$species->setLatinName($latinName);
			$species->setAuthor($author);

			$manager->persist($species);
		}

		$manager->flush();
	}
}