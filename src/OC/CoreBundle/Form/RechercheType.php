<?php

namespace OC\CoreBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datedebut', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
            ))
            ->add('datefin', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
            ))
            ->add('geo', ChoiceType::class)
            ->add('commune', ChoiceType::class)
            ->add('especebis',TextType::class)
            ->add('espece', ChoiceType::class)
            ->add('Rechercher',SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'OC\CoreBundle\Entity\Recherche'
//        ));
    }
}
