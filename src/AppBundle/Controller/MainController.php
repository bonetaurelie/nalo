<?php

namespace AppBundle\Controller;

use AppBundle\Form\ConnexionType;
use AppBundle\Form\InscriptionType;
use AppBundle\Form\NewMdpType;
use AppBundle\Form\ProfilType;
use AppBundle\Form\RechercheType;
use AppBundle\Form\ReinitialisationType;
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
    
    public function rechercheAction(){
        $form=  $this->get('form.factory')->create(RechercheType::class);

        return $this->render('Main/recherche.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detailRechercheAction(){
        return $this->render('Main/detailRecherche.html.twig');
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


	public function mentionsAction(){
		return $this->render('Main/mentions.html.twig');
	}


    public function saisieAction(){
        return $this->render('Main/saisie.html.twig');
    }

    
    public function observationsAction(){
        return $this->render('Main/observations.html.twig');
    }


    public function confirmationAction(){
        return $this->render('Main/confirmation.html.twig');
    }

    public function validationsAction(){
        return $this->render('Main/validations.html.twig');
    }

    public function detailValidationAction(){
        return $this->render('Main/detailObsValidation.html.twig');
    }
}

