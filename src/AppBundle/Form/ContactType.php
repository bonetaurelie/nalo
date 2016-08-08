<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', TextType::class, array(
            'constraints' => array(new NotBlank()),
	        'required' => false,
	        'label' => 'contact.prenom',
	        'translation_domain' => 'AppBundle',
        ))
        ->add('nom', TextType::class, array(
            'constraints' => array(new NotBlank()),
	        'required' => false,
	        'label' => 'contact.nom',
	        'translation_domain' => 'AppBundle',
        ))
        ->add('email', EmailType::class, array(
            'constraints' => array(new NotBlank(), new Email()),
	        'required' => false,
	        'label' => 'contact.email',
	        'translation_domain' => 'AppBundle',
        ))
        ->add('message', TextareaType::class, array(
            'constraints' => array(new NotBlank()),
	        'required' => false,
	        'label' => 'contact.message',
	        'translation_domain' => 'AppBundle',
        ))
        ->add('recaptcha', EWZRecaptchaType::class, array(
            'constraints' => array(new RecaptchaTrue()),
	        'required' => false
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_contact_type';
    }
}
