<?php

namespace OC\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterNotBlank()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['registration[firstName]'] = '';
        $form['registration[lastName]'] = '';
        $form['registration[email]'] = '';
        $form['registration[plainPassword][first]'] = '';

        $crawler = $client->submit($form);

        $this->assertContains("Veuillez renseigner un prénom.", $crawler->filter("#registration_firstName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un nom.", $crawler->filter("#registration_lastName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un e-mail.", $crawler->filter("#registration_email + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un mot de passe.", $crawler->filter("#registration_plainPassword_first + .help-block ul li")->text());
    }

    public function testRegisterNotValid()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['registration[firstName]'] = '';
        $form['registration[lastName]'] = '';
        $form['registration[email]'] = 'test';
        $form['registration[plainPassword][first]'] = 'test';
        $form['registration[plainPassword][second]'] = 'test';

        $crawler = $client->submit($form);

        $this->assertContains("Veuillez renseigner un prénom.", $crawler->filter("#registration_firstName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un nom.", $crawler->filter("#registration_lastName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un e-mail valide.", $crawler->filter("#registration_email + .help-block ul li")->text());
        $this->assertContains("Format non conforme.", $crawler->filter("#registration_plainPassword_first + .help-block ul li")->text());
    }

    public function testRegisterNotIdenticalPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/inscription');
        $form = $crawler->selectButton('Inscrivez-vous')->form();

        $form['registration[firstName]'] = '';
        $form['registration[lastName]'] = '';
        $form['registration[email]'] = 'test';
        $form['registration[plainPassword][first]'] = 'Test89-';
        $form['registration[plainPassword][second]'] = 'pas identique';

        $crawler = $client->submit($form);

        $this->assertContains("Veuillez renseigner un prénom.", $crawler->filter("#registration_firstName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un nom.", $crawler->filter("#registration_lastName + .help-block ul li")->text());
        $this->assertContains("Veuillez renseigner un e-mail valide.", $crawler->filter("#registration_email + .help-block ul li")->text());
        $this->assertContains("Les deux mots de passe ne sont pas identiques", $crawler->filter("#registration_plainPassword_first + .help-block ul li")->text());
    }

    public function testRegisterValid()
    {
        //En attente de trouver comment tester le formulaire sans enregistrer dans la base
//        $client = static::createClient();
//        $crawler = $client->request('GET','/inscription');
//        $form = $crawler->selectButton('Inscrivez-vous')->form();
//
//        $form['registration[firstName]'] = 'test';
//        $form['registration[lastName]'] = 'test';
//        $form['registration[email]'] = 'test';
//        $form['registration[plainPassword][first]'] = 'Test89-';
//        $form['registration[plainPassword][second]'] = 'Test89-';
//
//        $crawler = $client->submit($form);
//
//        $this->assertContains("Veuillez renseigner un prénom.", $crawler->filter("#registration_firstName + .help-block ul li")->text());
//        $this->assertContains("Veuillez renseigner un nom.", $crawler->filter("#registration_lastName + .help-block ul li")->text());
//        $this->assertContains("Veuillez renseigner un e-mail valide.", $crawler->filter("#registration_email + .help-block ul li")->text());
//        $this->assertContains("Les deux mots de passe ne sont pas identiques", $crawler->filter("#registration_plainPassword_first + .help-block ul li")->text());
    }
}
