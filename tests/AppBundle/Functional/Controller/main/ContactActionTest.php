<?php

namespace Tests\AppBundle\Functional\Controller\main;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactActionTest extends WebTestCase
{


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

		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_prenom + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_nom + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_email + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_message + .help-block ul li")->text());
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

		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_prenom + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_nom + .help-block ul li")->text());
		$this->assertContains("Cette valeur n'est pas une adresse email valide.", $crawler->filter("#contact_email + .help-block ul li")->text());
		$this->assertContains("Cette valeur ne doit pas être vide.", $crawler->filter("#contact_message + .help-block ul li")->text());

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