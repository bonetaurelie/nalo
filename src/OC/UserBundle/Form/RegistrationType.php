<?php

namespace OC\UserBundle\Form;

use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'form.firstName',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'form.lastName',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('rolePro', CheckboxType::class, array(
                'label' => 'form.rolePro',
                'translation_domain' => 'FOSUserBundle',
                'required'    => false
            ))
            ->remove('username')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('registration'),
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'ocuser_bundle_registration_type';
    }
}
