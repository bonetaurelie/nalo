<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Observation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObservationEntityTest extends WebTestCase
{
	private function getKernel()
	{
		$kernel = $this->createKernel();
		$kernel->boot();

		return $kernel;
	}

	public function testCustomValidator()
	{
//		$kernel = $this->getKernel();
//		$validator = $kernel->getContainer()->get('validator');
//
//		$violationList = $validator->validate(new Observation());
//
//		dump($violationList);
//
//		$this->assertEquals(1, $violationList->count());
		// or any other like:
//		$this->assertEquals('client not valid', $violationList[0]->getMessage());
	}
}