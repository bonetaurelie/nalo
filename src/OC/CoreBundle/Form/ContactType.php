<?php

namespace OC\CoreBundle\Form;

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
            'mapped' => false,
            'constraints' => array(new RecaptchaTrue()),
//            'error_bubbling' => true
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'occore_bundle_contact_type';
    }
}
