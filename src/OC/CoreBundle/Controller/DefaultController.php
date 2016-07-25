<?php

namespace OC\CoreBundle\Controller;

use OC\CoreBundle\Entity\Recherche;
use OC\CoreBundle\Form\ConnexionType;
use OC\CoreBundle\Form\ContactType;
use OC\CoreBundle\Form\InscriptionType;
use OC\CoreBundle\Form\NewMdpType;
use OC\CoreBundle\Form\ProfilType;
use OC\CoreBundle\Form\RechercheType;
use OC\CoreBundle\Form\ReinitialisationType;
use OC\CoreBundle\Form\SaisieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCCoreBundle:Default:index.html.twig');
    }
    
    
    public function associationAction(){
        return $this->render('OCCoreBundle:Default:association.html.twig');
    }
    
    public function rechercheAction(){
        $form=  $this->get('form.factory')->create(RechercheType::class);

        return $this->render('OCCoreBundle:Default:recherche.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detailRechercheAction(){
        return $this->render('OCCoreBundle:Default:detailRecherche.html.twig');
    }

    public function contactAction(){
        $form = $this->get('form.factory')->create(ContactType::class);


        // Check the method
        if ($form->isValid())
        {
            $contact = $form->getData();

            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject($contact['subject'])
                ->setFrom($contact['email'])
                ->setTo('xxxxx@gmail.com')
                ->setBody($contact['message']);

            $this->get('mailer')->send($message);

            // Launch the message flash
            $this->get('session')->setFlash('notice', 'Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.');
        }

        return $this->render('OCCoreBundle:Default:contact.html.twig',
            array(
                'form' => $form->createView(),
            ));

    }
    
    public function connexionAction(){
        $form=  $this->get('form.factory')->create(ConnexionType::class);

        return $this->render('OCCoreBundle:Default:connexion.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function mentionsAction(){
        return $this->render('OCCoreBundle:Default:mentions.html.twig');
    }
    
    public function saisieAction(){
        $form=  $this->get('form.factory')->create(SaisieType::class);

        return $this->render('OCCoreBundle:Default:saisie.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    public function observationsAction(){
        return $this->render('OCCoreBundle:Default:observations.html.twig');
    }

    public function inscriptionAction(){
        $form=  $this->get('form.factory')->create(InscriptionType::class);

        return $this->render('OCCoreBundle:Default:inscription.html.twig', array(
            'form' => $form->createView(),
        ));
       
    }
    
    public function mdpoublieAction(){
        $form=  $this->get('form.factory')->create(ReinitialisationType::class);

        return $this->render('OCCoreBundle:Default:mdpoublie.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function newmdpAction(){
        $form=  $this->get('form.factory')->create(NewMdpType::class);

        return $this->render('OCCoreBundle:Default:newmdp.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
    public function profilAction(){
        $form=  $this->get('form.factory')->create(ProfilType::class);

        return $this->render('OCCoreBundle:Default:profil.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }

    public function confirmationAction(){
        return $this->render('OCCoreBundle:Default:confirmation.html.twig');
    }

    public function validationsAction(){
        return $this->render('OCCoreBundle:Default:validations.html.twig');
    }
}

