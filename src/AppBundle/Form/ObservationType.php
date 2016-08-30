<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/08/16
 * Time: 17:35
 */

namespace AppBundle\Form;


use Anacona16\Bundle\DependentFormsBundle\Form\Type\DependentFormsType;
use AppBundle\Form\Type\SpeciesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class ObservationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('datetimeObservation', DateTimeType::class, array(
			'required' => false,
			'date_widget' => "single_text",
			'time_widget' => "single_text",
			'label_attr' => ['class' => 'col-lg-4'],
			'label' => 'observations.form.datetime',
			'translation_domain' => 'AppBundle',
			'constraints' => [new NotBlank(), new DateTime()],
		))
			->add('department', EntityType::class, array(
				'required' => false,
				'class' => 'AppBundle\Entity\locality\Department',
				'required' => true,
				'choice_label' => 'adminName',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('d')
						->orderBy('d.adminName', 'ASC');
				},
				"mapped" => false,
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'attr' => ['class' => 'form-control col-lg-8'],
				'label' => 'observations.form.department',
				'translation_domain' => 'AppBundle',
				'constraints' => [new NotBlank()],
			))
			->add('city', DependentFormsType::class, array(
				'required' => false,
				'entity_alias' => 'city_by_department',
				'empty_value' => 'Choisir une ville',
				'parent_field' => 'department',
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'attr' => ['class' => 'form-control col-lg-8'],
				'label' => 'observations.form.city',
				'translation_domain' => 'AppBundle',
				'constraints' => [new NotBlank()],
			))
            ->add('locality', TextType::class, array(
	            'required' => false,
                'label' => 'observations.form.locality',
                'translation_domain' => 'AppBundle'
            ))
			->add('species', SpeciesType::class, array(
				'required' => false,
				'filter_css_class' => 'col-lg-5 no-padding',
				'filter_choice_css_class' => 'col-lg-6 no-padding',
				'constraints' => [new NotBlank()],
			))
			->add('nbIndividual', IntegerType::class, array(
				'required' => false,
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'label' => 'observations.form.nb_individual',
				'translation_domain' => 'AppBundle'
			))
			->add('comment', TextareaType::class, array(
				'required' => false,
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'attr' => ['class' => 'form-control col-lg-8', 'style' => 'height:150px'],
				'label' => 'observations.form.comment',
				'translation_domain' => 'AppBundle',
			))
			->add('images', CollectionType::class, array(
				'required' => false,
				'entry_type' => ImageType::class,
				'constraints' => [new Valid()],
				'allow_add' => true,
				'by_reference' => false,
				'allow_delete' => true,
				'prototype' => true,
				'label' => 'observations.form.image.title',
				'translation_domain' => 'AppBundle',
                'entry_options'  => array(
                    'required'  => false,
                    'attr'      => array('class' => 'image-box'),
                    'label' => 'observations.form.image.label',
                    'translation_domain' => 'AppBundle',
                )
            ))
            ->add('longitude', HiddenType::class, array(
	            'required' => false,
	            'constraints' => [new NotBlank()],
            ))
            ->add('latitude', HiddenType::class, array(
	            'required' => false,
	            'constraints' => [new NotBlank()],
            ))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Observation',
		));

	}
}