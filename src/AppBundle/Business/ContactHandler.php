<?php

namespace AppBundle\Business;

use AppBundle\Form\ContactType;
use AppBundle\Services\MailerTemplating;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * Class ContactService
 *
 * Permet d'utiliser le formulaire de contact avec l'envoie ou non d'un e-mail
 *
 * @package OC\CoreBundle\Business
 */
class ContactHandler
{
    private $form;
    private $mailer;
    private $flashBag;

    private $fromMail;
    private $toMail;
    private $data;

    public function __construct(FormFactory $formFactory, MailerTemplating $mailer,FlashBag $flashBag, $fromMail, $toMail)
    {
        //Création des données vides pour le formulaire de contact, pas besoin de créer une entité pour ça
        $this->Data = array('prenom' => '','nom' => '','email' => '', 'message' => '', 'recaptcha' => false,);
        //Création du formulaire de contact via le service form.factory
        $this->form = $formFactory->create(ContactType::class, $this->Data);
        //récupération du service d'envoie d'e-mail personnalisé
        $this->mailer = $mailer;
        //récupération du service de message flash
        $this->flashBag = $flashBag;
        //initialisation de l'e-mail d'envoie
        $this->fromMail = $fromMail;
        //initialisation de l'e-mail destinataire
        $this->toMail = $toMail;
    }

    /**
     * Renvoie la vue du formulaire
     * @return \Symfony\Component\Form\FormView
     */
    public function getFormView()
    {
        return $this->form->createView();
    }

    /**
     * Traite les données du formulaire, si tout est valide retourne true
     * @param Request $request
     * @return bool
     */
    public function formTreatment(Request $request)
    {
        $this->form->handleRequest($request);

        //Vérification si le formulaire est valide ou non
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            //récupération des données du formulaire
            $this->data = $this->form->getData();

            return true;
        }

        return false;
    }

    /**
     * Envoie un e-mail avec les informations du contact
     * @param string $subject
     * @param string $sucessMessage
     * @param string $template
     * @return bool
     */
    public function sendMail($subject = "Site NALO : message via la page contact", $sucessMessage = "Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.", $template = 'Email/contact.html.twig')
    {
        $this->verifEmptyData();//Vérifie que les données sont bien générées

        try {
            $this->mailer->send($this->data,$subject,$this->fromMail,$this->toMail,$template);

            $this->flashBag->add('notice', $sucessMessage);

            return true;
        } catch (\Exception $e) {//si il y a une erreur
            $this->flashBag->add('error', "Une erreur est intervenue, si l'erreur persiste veuillez contacter l'administrateur du site.");

            return false;
        }
    }

    /**
     * Réuni les actions formTreatment et sendMail en une seule méthode pour en facilité l'usage
     * @param Request $request
     * @param string $subject
     * @param string $sucessMessage
     * @param string $template
     */
    public function formTreatmentAndSendMail(Request $request, $subject = "Site NALO : message via la page contact", $sucessMessage = "Merci de nous avoir contacté, nous répondrons à vos questions dans les plus brefs délais.", $template = 'Email/contact.html.twig'){
        if(true === $this->formTreatment($request)){
            $this->sendMail($subject, $sucessMessage, $template);
        }
    }

    /**
     * Vérifie que les données sont bien générées
     * @throws \Exception
     */
    private function verifEmptyData(){
        if(null === $this->data || count($this->data) === 0){
            throw new \Exception("Please use formTreatment method before use that!");
        }
    }
}