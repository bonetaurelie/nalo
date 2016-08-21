<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('Main/index.html.twig');
    }
    
    
    public function associationAction(){
        return $this->render('Main/association.html.twig');
    }

	public function mentionsAction(){
		return $this->render('Main/mentions.html.twig');
	}

    /**
     * Page contact
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request){
        //Récupération du formulaire de contact
        $contactBusiness =  $this->get('app.business_contact');
        //Traitement du formulaire et envoie d'un e-mail si validé
        $contactBusiness->formTreatmentAndSendMail($request);

        return $this->render('Main/contact.html.twig', array('form' => $contactBusiness->getFormView()));
    }
}

