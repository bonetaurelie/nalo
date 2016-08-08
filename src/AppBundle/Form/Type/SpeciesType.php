<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/08/16
 * Time: 17:49
 */

namespace AppBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
//		$builder->add('species', EntityType::class, array(
//			'class' => 'AppBundle\Entity\Species',
//			'choice_label' => 'frenchName',
//			'placeholder' => 'search.species.placeholder',
//			'label' => 'search.species.label',
//			'translation_domain' => 'AppBundle'
//		));

		$builder->addViewTransformer();
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array(
				'm' => 'Male',
				'f' => 'Female',
			)
		));
	}

	public function getParent()
	{
		return TextType::class;
	}
}