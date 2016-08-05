<?php

namespace OC\UserBundle\Business;


use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\RegistrationType;
use Symfony\Component\Form\FormFactory;

class RegistrationService
{
    private $form;

    public function __construct(FormFactory $formFactory)
    {
        $user = new User();
        $this->form = $formFactory->create(RegistrationType::class, $user);
    }

    public function getFormView()
    {
        return $this->form->createView();
    }
}