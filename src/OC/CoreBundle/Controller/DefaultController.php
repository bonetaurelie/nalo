<?php

namespace OC\CoreBundle\Controller;

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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    public function contactAction(Request $request){
        $contactDefaultData = array(
            'prenom'    => '',
            'nom'       => '',
            'email'     => '',
            'message'   => '',
        );

        $form = $this->createFormBuilder($contactDefaultData)
            ->add('prenom', TextType::class, array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('nom', TextType::class, array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new Email()
                )
            ))
            ->add('message', TextareaType::class, array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        // Check the method
        if ($form->isValid())
        {

            try{
                $contact = $form->getData();

                $mailMessage =  "Bonjour, \r\n ".$contact['nom']." ".$contact['prenom'].
                    " vous a laissé un message sur le site NALO \r\n Voici son message: \r\n \r\n".$contact['message'].
                    " \r\n\r\n vous pour lui répondre directement via son e-mail : ".$contact['message'].
                    " \r\n\r\n ".
                    " Cordialement, \r\n l'association NALO";

                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject("Site NALO : message via la page contact")
                    ->setFrom($this->getParameter('robot_email'))
                    ->setTo($this->getParameter('contact_email'))
                    ->setBody($mailMessage);

                $rslt = $this->get('mailer')->send($message);

                //si le service d'envoi d'e-mail retourne autre chose que 1 ou true
                if($rslt != 1){//@todo il faut que je vérifie ce qu'il retourne exactement
                    $flashMessage  = "Une erreur est intervenue, si l'erreur persiste veuillez contacter l'administrateur du site.";

                    //on teste l'environnement si on est en test ou en dev on affiche l'erreur à l'erreur
                    if(in_array($this->get('kernel')->getEnvironment(), array('test','dev'))){
                        $flashMessage  .= "\r\n".$rslt;
                    }

                    // Launch the message flash
                    $this->addFlash('danger', $flashMessage);
                }
                else{
                    // Launch the message flash
                    $this->addFlash('notice', 'Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.');
                }
            }
            catch(\Exception $e){
                $flashMessage  = "Une erreur est intervenue, si l'erreur persiste veuillez contacter l'administrateur du site.";

                //on teste l'environnement si on est en test ou en dev on affiche l'erreur à l'erreur
                if(in_array($this->get('kernel')->getEnvironment(), array('test','dev'))){
                    $flashMessage  .= "\r\n".$e->getMessage();
                }

                // Launch the message flash
                $this->addFlash('danger', $flashMessage);
            }

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

    public function detailValidationAction(){
        return $this->render('OCCoreBundle:Default:detailObsValidation.html.twig');
    }
}

