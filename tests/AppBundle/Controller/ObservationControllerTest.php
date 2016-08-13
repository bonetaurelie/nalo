<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObservationControllerTest extends WebTestCase
{

	public function connexionClient(Client $client)
	{
		$crawler = $client->request('GET', '/connexion');

		$form = $crawler->filter("form")->form();

		$form['_username'] = 'test.amateur@test.fr';
		$form['_password'] = 'test1A-';

		$client->submit($form);
	}

	public function getObservationsUrlList()
	{
		return ['/mes-observations', '/ajouter-une-observation'];
	}

    /**
     * Vérifie que les pages liés aux observations s'affichent bien
     */
    public function testAccessViews()
    {
	    $client = static::createClient();

	    $this->connexionClient($client);

	    foreach ($this->getObservationsUrlList() as $url){
	    	echo "accès url : ".$url."\r\n";
		    $client->request('GET', $url);
		    $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    }
    }

    public function testAddObservation(){

    }
}
