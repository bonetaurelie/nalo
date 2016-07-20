<?php

namespace OC\CoreBundle\Controller;

use OC\CoreBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        return $this->render('OCCoreBundle:Default:recherche.html.twig');
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
}

