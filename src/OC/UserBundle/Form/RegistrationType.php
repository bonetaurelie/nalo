<?php

namespace OC\UserBundle\Form;

use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                'translation_domain' => 'FOSUserBundle',
	            'required' => false
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'form.lastName',
                'translation_domain' => 'FOSUserBundle',
	            'required' => false
            ))
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
	            'required' => false
            ))
	        ->add('plainPassword', RepeatedType::class, array(
		        'type' => PasswordType::class,
		        'required' => false,
		        'options' => array('translation_domain' => 'FOSUserBundle'),
		        'first_options' => array('label' => 'form.password'),
		        'second_options' => array('label' => 'form.password_confirmation'),
		        'invalid_message' => 'fos_user.password.mismatch',
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
            'validation_groups' => array('Registration'),
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

	public function getBlockPrefix()
	{
		return 'ocuser_bundle_registration_type';
	}

	// For Symfony 2.x
	public function getName()
	{
		return $this->getBlockPrefix();
	}
}
