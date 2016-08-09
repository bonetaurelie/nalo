<?php

namespace AppBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }


	public function getParent()
	{
		return EntityType::class;
	}

	public function getBlockPrefix()
	{
		return 'species';
	}
}