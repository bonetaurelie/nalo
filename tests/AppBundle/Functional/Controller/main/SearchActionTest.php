<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 22/08/16
 * Time: 16:22
 */

namespace Tests\AppBundle\Functional\Controller\main;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SearchActionTest extends WebTestCase
{
	/**
	 * Test if error when the form fields are empty
	 */
	public function	testFormAllEmpty()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/recherche');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$form = $crawler->filter('form[name=recherche]')->form();

		$form['recherche[startDate]'] = '';
		$form['recherche[endDate]'] = '';

//		$departmentValues = $form['recherche[department]']->availableOptionValues();
//		$form['recherche[department]']->select('');
//
//		$cityValues = $form['recherche[city]']->availableOptionValues();
//		$form['recherche[city]']->select($cityValues[0]);
//
//		$form['recherche[species]'] = '';

		$crawler = $client->submit($form);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

//		$this->assertContains("Cette valeur ne doit pas être vide. test", $crawler->filter("#recherche_startDate + .help-block ul li")->text());
//		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#recherche_endDate + .help-block ul li")->text());
//		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#recherche_department + .help-block ul li")->text());
//		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#recherche_city + .help-block ul li")->text());
//		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#recherche_species + .help-block ul li")->text());
	}
}