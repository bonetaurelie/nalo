<?php

namespace OC\CoreBundle\Controller;

use OC\CoreBundle\Form\ConnexionType;
use OC\CoreBundle\Form\ContactType;
use OC\CoreBundle\Form\InscriptionType;
use OC\CoreBundle\Form\NewMdpType;
use OC\CoreBundle\Form\ProfilType;
use OC\CoreBundle\Form\RechercheType;
use OC\CoreBundle\Form\ReinitialisationType;
use OC\CoreBundle\Form\SaisieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCCoreBundle:Main:index.html.twig');
    }
    
    
    public function associationAction(){
        return $this->render('OCCoreBundle:Main:association.html.twig');
    }
    
    public function rechercheAction(){
        $form=  $this->get('form.factory')->create(RechercheType::class);

        return $this->render('OCCoreBundle:Main:recherche.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detailRechercheAction(){
        return $this->render('OCCoreBundle:Main:detailRecherche.html.twig');
    }

    public function contactAction(Request $request){
        //Création des données vides pour le formulaire de contact, pas besoin de créer une entité pour ça
        $contactDefaultData = array(
            'prenom'    => '',
            'nom'       => '',
            'email'     => '',
            'message'   => '',
            'recaptcha' => false,
        );

        $form = $this->createForm(ContactType::class, $contactDefaultData);
        $form->handleRequest($request);

        //Vérification si le formulaire est valide ou non
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération des données du formulaire
            $contact = $form->getData();

            $verif = $this->sendingEmail(
                $contact,
                "Site NALO : message via la page contact",
                $this->getParameter('robot_email'), $this->getParameter('contact_email'),
                'OCCoreBundle:Email:contact.html.twig'
            );

            if (true === $verif) {
                $this->addFlash('notice', 'Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.');
            } else{
                $this->addFlash('error', "Une erreur est intervenue, si l'erreur persiste veuillez contacter l'administrateur du site.<br />" . $verif);
            }
        }

        return $this->render('OCCoreBundle:Main:contact.html.twig', array('form' => $form->createView()));
    }
    
    public function connexionAction(){
        $form=  $this->get('form.factory')->create(ConnexionType::class);

        return $this->render('OCCoreBundle:Main:connexion.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function mentionsAction(){
        return $this->render('OCCoreBundle:Main:mentions.html.twig');
    }
    
    public function saisieAction(){
        $form=  $this->get('form.factory')->create(SaisieType::class);

        return $this->render('OCCoreBundle:Main:saisie.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    public function observationsAction(){
        return $this->render('OCCoreBundle:Main:observations.html.twig');
    }

    public function inscriptionAction(){
        $form=  $this->get('form.factory')->create(InscriptionType::class);

        return $this->render('OCCoreBundle:Main:inscription.html.twig', array(
            'form' => $form->createView(),
        ));
       
    }
    
    public function mdpoublieAction(){
        $form=  $this->get('form.factory')->create(ReinitialisationType::class);

        return $this->render('OCCoreBundle:Main:mdpoublie.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function newmdpAction(){
        $form=  $this->get('form.factory')->create(NewMdpType::class);

        return $this->render('OCCoreBundle:Main:newmdp.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
    public function profilAction(){
        $form=  $this->get('form.factory')->create(ProfilType::class);

        return $this->render('OCCoreBundle:Main:profil.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }

    public function confirmationAction(){
        return $this->render('OCCoreBundle:Main:confirmation.html.twig');
    }

    public function validationsAction(){
        return $this->render('OCCoreBundle:Main:validations.html.twig');
    }

    public function detailValidationAction(){
        return $this->render('OCCoreBundle:Main:detailObsValidation.html.twig');
    }

    private function sendingEmail(array $tplVariables, $subject, $from, $to, $template)
    {
        try {
            //récupération du service d'envoie d'e-mail personnalisé
            $mailerTpl = $this->get('oc_core.mailer_templating');
            $mailerTpl->send(
                $tplVariables,
                $subject,
                $from,
                $to,
                $template
            );

           return true;

        } catch (\Exception $e) {//si il y a une erreur, on la retourne
            return $e->getMessage();
        }
    }
}

