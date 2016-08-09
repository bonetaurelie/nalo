<?php
namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use SimpleFilterListEntityBundle\Form\Type\SimpleFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'frenchName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.frenchName', 'ASC');
                },
                'placeholder' => 'search.species.placeholder',
                'label' => 'search.species.label',
                'popover_title' => 'search.species.popover_title',
                'popover_content' => 'search.species.popover_content',
                'translation_domain' => 'AppBundle'
            )
        );
    }

	public function getParent()
	{
		return SimpleFilterType::class;
	}

	public function getBlockPrefix()
	{
		return 'species';
	}
}