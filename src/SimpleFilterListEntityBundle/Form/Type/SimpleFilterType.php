<?php
namespace SimpleFilterListEntityBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class SimpleFilterType extends AbstractType
{

	/**
	 * @var PropertyAccessorInterface
	 */
	private $propertyAccessor;

	/**
	 * @param PropertyAccessorInterface $propertyAccessor
	 */
	public function __construct(PropertyAccessorInterface $propertyAccessor)
	{
		$this->propertyAccessor = $propertyAccessor;
	}

	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function finishView(FormView $view, FormInterface $form, array $options)
	{
		parent::finishView($view, $form, $options);

		$view->vars['popover_title'] = $options['popover_title'];
		$view->vars['popover_content'] = $options['popover_content'];
	}

    public function configureOptions(OptionsResolver $resolver)
    {
	    parent::configureOptions($resolver);

	    $resolver->setDefaults(
		    array(
			    'popover_title' => '',
			    'popover_content' => '',
		    )
	    );
    }

	public function getParent()
	{
		return EntityType::class;
	}

	public function getBlockPrefix()
	{
		return 'filter_list';
	}
}