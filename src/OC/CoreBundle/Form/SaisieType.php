<?php

namespace OC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y'))
            ->add('departement',ChoiceType::class)
            ->add('commune',ChoiceType::class)
            ->add('lieu',ChoiceType::class)
            ->add('especebis',TextType::class)
            ->add('espece',ChoiceType::class)
            ->add('indiv',IntegerType::class)
            ->add('commentaire',TextareaType::class)
            ->add('Envoyer',SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\CoreBundle\Entity\Saisie'
        ));
    }
}
