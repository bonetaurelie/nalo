<?php
namespace SimpleFilterListEntityBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 *
 * FormType for to filter simply a select box with a simple input text
 *
 * It's my first custom formType :-)
 *
 * Class SimpleFilterType
 *
 * @author Nicolas PIN <pin.nicolas@free.fr>
 *
 * @package SimpleFilterListEntityBundle\Form\Type
 */
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
		$view->vars['filter_css_class'] = $options['filter_css_class'];
		$view->vars['filter_choice_css_class'] = $options['filter_choice_css_class'];
		$view->vars['input_css_class'] = $options['input_css_class'];
		$view->vars['icon_css_class'] = $options['icon_css_class'];
	}

    public function configureOptions(OptionsResolver $resolver)
    {
	    parent::configureOptions($resolver);

	    $resolver->setDefaults(
		    array(
			    'popover_title' => '',
			    'popover_content' => '',
			    'filter_css_class' => 'col-lg-5',
			    'filter_choice_css_class' => 'col-lg-6',
			    'input_css_class' => 'form-control',
			    'icon_css_class' => 'col-lg-1',
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