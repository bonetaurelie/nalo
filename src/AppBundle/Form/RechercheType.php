<?php

namespace AppBundle\Form;

use Anacona16\Bundle\DependentFormsBundle\Form\Type\DependentFormsType;
use AppBundle\Form\Type\SpeciesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('startDate', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
            ))
            ->add('endDate', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
            ))
            ->add('department', EntityType::class, array(
            	'class' => 'AppBundle\Entity\locality\Department',
	            'required' => true,
	            'choice_label' => 'adminName',
	            'query_builder' => function (EntityRepository $er) {
		            return $er->createQueryBuilder('d')
			            ->orderBy('d.adminName', 'ASC');
	            },
            ))
            ->add('city', DependentFormsType::class, array(
            	'entity_alias' => 'city_by_department',
	            'empty_value' => 'locality.choice_city',
	            'parent_field' => 'department',
	            'translation_domain' => 'AppBundle'
            ))
            ->add('species', SpeciesType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
