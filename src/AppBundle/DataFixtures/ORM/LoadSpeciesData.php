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
		/** @var KernelInterface $kernel */
//		$kernel = $this->container->get('kernel');
//
		$root =  $this->container->getParameter('kernel.root_dir').'/';
//
		$csv =Reader::createFromPath( $root . 'Resources/dataSources/TAXREF_utf8.csv');
		$csv->setDelimiter(';');
////get the first row, usually the CSV header
//		$headers = $csv->fetchOne();

		$res = $csv->setOffset(1)->fetchAll();//->setLimit(25)

//		var_dump($headers);

		foreach($res as $row){
			$species = new Species();
			$species->setFrenchName($row[12]); // NOM VALIDE nom valide
			$species->setLatinName($row[9]); //LB_NAME nom latin
			$species->setAuthor($row[10]); // LB_AUTEUR

			$manager->persist($species);
		}


		$manager->flush();
	}
}