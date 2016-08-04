<?php

namespace OC\CoreBundle\Controller;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use OC\CoreBundle\Form\ConnexionType;
use OC\CoreBundle\Form\InscriptionType;
use OC\CoreBundle\Form\NewMdpType;
use OC\CoreBundle\Form\ProfilType;
use OC\CoreBundle\Form\RechercheType;
use OC\CoreBundle\Form\ReinitialisationType;
use OC\CoreBundle\Form\SaisieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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
//        //récupération du service gérant le captcha de google
//        $googleReCaptcha = $this->get('oc_core.google_recaptcha');
        //Création des données vides pour le formulaire de contact, pas besoin de créer une entité pour ça
        $contactDefaultData = array(
            'prenom'    => '',
            'nom'       => '',
            'email'     => '',
            'message'   => '',
        );
        //création d'un formulaire sans entity
        $form = $this->createFormBuilder($contactDefaultData)
            ->add('prenom', TextType::class, array(
                'constraints' => array(new NotBlank())
            ))
            ->add('nom', TextType::class, array(
                'constraints' => array(new NotBlank())
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(new Email())
            ))
            ->add('message', TextareaType::class, array(
                'constraints' => array(new NotBlank())
            ))
            ->add('recaptcha', EWZRecaptchaType::class, array(
                'constraints' => array(
                    new RecaptchaTrue()
                )
            ))
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        //Vérification si le formulaire est valide ou non
        if(!$form->isValid()){
            return $this->render('OCCoreBundle:Main:contact.html.twig',
                array(
                    'form' => $form->createView(),
                ));
        }

        //Si le formulaire est valide :

        //récupération des données du formulaire
        $contact = $form->getData();

        try{
            //Préparation du message
            $body =  "Bonjour, <br>".$contact['nom']." ".$contact['prenom'].
                " vous a laissé un message sur le site NALO <br> Voici son message: <br><br>".$contact['message'].
                " <br><br> vous pour lui répondre directement via son e-mail : ".$contact['email'].
                " <br><br> ".
                " Cordialement, <br> l'association NALO";

            //récupération du service d'envoie d'e-mail personnalisé
            $mailerTpl = $this->get('oc_core.mailer_templating');

            $mailerTpl->send(
                array('body' => $body),
                "Site NALO : message via la page contact",
                $this->getParameter('robot_email'),
                $this->getParameter('contact_email'),
                'OCCoreBundle:Email:default.html.twig'
            );

            $this->addFlash('notice', 'Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.');

        }
        catch(\Exception $e){//si il y a une erreur, on la retourne si on est en mode test ou dev

            $flashMessage  = "Une erreur est intervenue, si l'erreur persiste veuillez contacter l'administrateur du site.";

            //on teste l'environnement si on est en test ou en dev on affiche l'erreur à l'erreur
            if(in_array($this->get('kernel')->getEnvironment(), array('test','dev'))){
                $flashMessage  .= "\r\n".$e->getMessage();
            }

            $this->addFlash('error', $flashMessage);
        }

        return $this->render('OCCoreBundle:Main:contact.html.twig',
            array('form' => $form->createView()));
    }


    public function mentionsAction(){
        return $this->render('OCCoreBundle:Main:mentions.html.twig');
    }

    
    public function observationsAction(){
        return $this->render('OCCoreBundle:Main:observations.html.twig');
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
}

