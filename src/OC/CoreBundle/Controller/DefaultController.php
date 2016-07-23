<?php

namespace OC\CoreBundle\Controller;

use OC\CoreBundle\Entity\Recherche;
use OC\CoreBundle\Form\ContactType;
use OC\CoreBundle\Form\RechercheType;
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
        return $this->render('OCCoreBundle:Default:connexion.html.twig');
    }

    public function mentionsAction(){
        return $this->render('OCCoreBundle:Default:mentions.html.twig');
    }
    
    public function saisieAction(){
        return $this->render('OCCoreBundle:Default:saisie.html.twig');
    }
    
    public function observationsAction(){
        return $this->render('OCCoreBundle:Default:observations.html.twig');
    }

    public function inscriptionAction(){
        return $this->render('OCCoreBundle:Default:inscription.html.twig');
    }
    
    public function mdpoublieAction(){
        return $this->render('OCCoreBundle:Default:mdpoublie.html.twig');
    }

    public function newmdpAction(){
        return $this->render('OCCoreBundle:Default:newmdp.html.twig');
    }
}

