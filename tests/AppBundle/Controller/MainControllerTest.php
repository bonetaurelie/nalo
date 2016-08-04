<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * Vérifie que la page d'accueil s'affiche bien avec le bon titre
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('NOS AMIS LES OISEAUX', $crawler->filter('.accueil h1')->text());
    }

    /**
     * Vérifie que la page contact s'affiche bien
     */
    public function testContact(){
        $client = static::createClient();
        $crawler = $client->request('GET','/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $heading = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('CONTACTER L\'ASSOCIATION', $heading);
    }

    /**
     * Vérifie dans la page contact que si tout les champs sont vide on est bien
     * l'erreur "Cette valeur ne doit pas être vide."
     * pour chaque champ
     */
    public function testContactFormNotBlankFields(){
        $client = static::createClient();
        $crawler = $client->request('GET','/contact');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['contact[prenom]'] = '';
        $form['contact[nom]'] = '';
        $form['contact[email]'] = '';
        $form['contact[message]'] = '';


        $crawler = $client->submit($form);

        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_prenom + ul li")->text());
        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_nom + ul li")->text());
        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_email + ul li")->text());
        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_message + ul li")->text());
    }

    /**
     * Vérifie dans la page contact que si le champ e-mail n'est pas correct on est
     * l'erreur "Cette valeur n'est pas une adresse email valide."
     *
     */
    public function testContactFormNotEmailField(){
        $client = static::createClient();
        $crawler = $client->request('GET','/contact');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['contact[prenom]'] = '';
        $form['contact[nom]'] = '';
        $form['contact[email]'] = 'test@test';
        $form['contact[message]'] = '';

        $crawler = $client->submit($form);
        //Cette valeur n'est pas une adresse email valide.
        //Cette valeur ne doit pas être vide.
        //Vous devez cocher la case "Je ne suis pas un robot" -> le recaptcha ne peut pas être testé avec un robot :-)

        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_prenom + ul li")->text());
        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_nom + ul li")->text());
        $this->assertContains("Cette valeur n'est pas une adresse email valide.", $crawler->filter("#contact_email + ul li")->text());
        $this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_message + ul li")->text());

    }

    /**
     * Verifie sur la page contact que si on rempli toutes les données correctement ça fonctionne
     * (Affiche d'un message flash : Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.)
     *
     */
    public function testContactFormValidData(){
        $client = static::createClient();
        $crawler = $client->request('GET','/contact');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['contact[prenom]'] = 'Nicolas';
        $form['contact[nom]'] = 'PIN';
        $form['contact[email]'] = 'test@test.fr';
        $form['contact[message]'] = 'test formulaire';

        $crawler = $client->submit($form);

        $this->assertContains("Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.", $crawler->filter(".alert")->text());
    }
}
