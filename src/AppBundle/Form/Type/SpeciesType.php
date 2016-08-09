<?php

namespace AppBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

//implements DataTransformerInterface
class SpeciesType extends AbstractType
{
//	public function buildForm(FormBuilderInterface $builder, array $options)
//	{
////		$builder->add('species', EntityType::class, array(
////			'class' => 'AppBundle\Entity\Species',
////			'choice_label' => 'frenchName',
////			'placeholder' => 'search.species.placeholder',
////			'label' => 'search.species.label',
////			'translation_domain' => 'AppBundle'
////		));
//
////		$builder->addViewTransformer();
//	}

//	public function buildView(FormView $view, FormInterface $form, array $options)
//	{
//		$view->vars = array_replace($view->vars, array(
//			'ewz_recaptcha_enabled' => $this->enabled,
//			'ewz_recaptcha_ajax'    => $this->ajax,
//		));
//
//		if (!$this->enabled) {
//			return;
//		}
//
//		if (!isset($options['language'])) {
//			$options['language'] = $this->language;
//		}
//
//		if (!$this->ajax) {
//			$view->vars = array_replace($view->vars, array(
//				'url_challenge' => sprintf('%s?hl=%s', self::RECAPTCHA_API_SERVER, $options['language']),
//				'public_key'    => $this->publicKey,
//			));
//		} else {
//			$view->vars = array_replace($view->vars, array(
//				'url_api'    => self::RECAPTCHA_API_JS_SERVER,
//				'public_key' => $this->publicKey,
//			));
//		}
//	}

	public function configureOptions(OptionsResolver $resolver)
	{
//		$resolver->setDefaults(array(
//			'choices' => array(
//				'm' => 'Male',
//				'f' => 'Female',
//			)
//		));
	}

	public function getParent()
	{
		return EntityType::class;
	}

	public function getBlockPrefix()
	{
		return 'species';
	}
//
//	public function transform($value)
//	{
//		// TODO: Implement transform() method.
//	}
//
//	public function reverseTransform($value)
//	{
//		// TODO: Implement reverseTransform() method.
//	}
}