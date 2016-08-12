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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class ObservationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('datetimeObservation', DateTimeType::class, array(
			'date_widget' => "single_text",
			'time_widget' => "single_text",
			'label_attr' => ['class' => 'col-lg-4'],
			'label' => 'observations.form.datetime',
			'translation_domain' => 'AppBundle'
		))
			->add('department', EntityType::class, array(
				'class' => 'AppBundle\Entity\locality\Department',
				'required' => true,
				'choice_label' => 'adminName',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('d')
						->orderBy('d.adminName', 'ASC');
				},
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'attr' => ['class' => 'form-control col-lg-8'],
				'label' => 'observations.form.department',
				'translation_domain' => 'AppBundle'
			))
			->add('city', DependentFormsType::class, array(
				'entity_alias' => 'city_by_department',
				'empty_value' => 'Choisir une ville',
				'parent_field' => 'department',
				'label_attr' => ['class' => 'label-control col-lg-4'],
				'attr' => ['class' => 'form-control col-lg-8'],
				'label' => 'observations.form.city',
				'translation_domain' => 'AppBundle'
			))
			->add('species', SpeciesType::class)
		;
	}
}