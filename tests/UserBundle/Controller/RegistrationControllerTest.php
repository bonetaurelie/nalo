<?php

namespace Tests\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

	/**
	 * @var EntityManager
	 */
	private $_em;

	protected function setUp()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
		$this->_em->beginTransaction();
	}

	/**
	 * Rollback changes.
	 */
	public function tearDown()
	{
		$this->_em->rollback();
	}


    public function testRegisterNotBlank()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['fos_user_registration_form[firstName]'] = '';
        $form['fos_user_registration_form[lastName]'] = '';
        $form['fos_user_registration_form[email]'] = '';
        $form['fos_user_registration_form[plainPassword][first]'] = '';

        $crawler = $client->submit($form);

        $this->assertContains("Veuillez renseigner un prénom.", $crawler->filter("#fos_user_registration_form_firstName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un nom.", $crawler->filter("#fos_user_registration_form_lastName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un e-mail.", $crawler->filter("#fos_user_registration_form_email + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un mot de passe.", $crawler->filter("#fos_user_registration_form_plainPassword_first + .help-block ul li")->text());
    }

    public function testRegisterNotValid()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['fos_user_registration_form[email]'] = 'test';
        $form['fos_user_registration_form[plainPassword][first]'] = 'testA8';
        $form['fos_user_registration_form[plainPassword][second]'] = 'testA8';

        $crawler = $client->submit($form);

        $this->assertContains("Veuillez renseigner un e-mail valide.", $crawler->filter("#fos_user_registration_form_email + .help-block ul li")->text());
        $this->assertContains("Format non conforme.", $crawler->filter("#fos_user_registration_form_plainPassword_first + .help-block ul li")->text());
    }

	public function testRegisterEmailExist()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/inscription');
		$form = $crawler->selectButton('Inscrivez-vous')->form();

		$form['fos_user_registration_form[email]'] = 'test.amateur@test.fr';

		$crawler = $client->submit($form);

		$this->assertContains("L'e-mail saisi est déjà utilisé sur un autre compte.", $crawler->filter("#fos_user_registration_form_email + .help-block ul li")->text());

	}

    public function testRegisterNotIdenticalPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['fos_user_registration_form[plainPassword][first]'] = 'Test89-';
        $form['fos_user_registration_form[plainPassword][second]'] = 'pas identique';

        $crawler = $client->submit($form);

        $this->assertContains("Les deux mots de passe ne sont pas identiques", $crawler->filter("#fos_user_registration_form_plainPassword_first + .help-block ul li")->text());
    }

    public function testRegisterValid()
    {
        //En attente de trouver comment tester le formulaire sans enregistrer dans la base
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['fos_user_registration_form[firstName]'] = 'test';
        $form['fos_user_registration_form[lastName]'] = 'test';
        $form['fos_user_registration_form[email]'] = 'test@test.fr';
        $form['fos_user_registration_form[plainPassword][first]'] = 'Test89-';
        $form['fos_user_registration_form[plainPassword][second]'] = 'Test89-';

        $crawler = $client->submit($form);

	    $this->assertTrue($client->getResponse()->isRedirect());

	    $crawler = $client->followRedirect();

	    $this->assertContains("Bienvenue sur notre site !", $crawler->filter(".alert")->text());
    }
}
