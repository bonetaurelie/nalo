<?php

namespace AppBundle\Form;

use Anacona16\Bundle\DependentFormsBundle\Form\Type\DependentFormsType;
use AppBundle\Form\Type\SpeciesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SearchType extends AbstractType
{

	/**
	 * @var Translator
	 */
	protected $translator;

	public function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
	            'constraints' => array(
	            	new NotBlank(),
	            ),
            ))
            ->add('endDate', DateType::class,array(
                'widget'=>'single_text', 'format' => 'dd/MM/y',
	            'constraints' => array(
	            	new NotBlank(),
	            ),
            ))
            ->add('department', EntityType::class, array(
            	'class' => 'AppBundle\Entity\locality\Department',
	            'required' => true,
	            'choice_label' => 'adminName',
	            'placeholder' => 'search.department_all',
	            'translation_domain' => 'AppBundle',
	            'query_builder' => function (EntityRepository $er) {
		            return $er->createQueryBuilder('d')
			            ->orderBy('d.adminName', 'ASC');
	            }
            ))
            ->add('city', DependentFormsType::class, array(
            	'entity_alias' => 'city_by_department',
	            'empty_value' => 'Choisir une ville',
	            'parent_field' => 'department'
            ))
            ->add('species', SpeciesType::class, array(
                'filter_css_class' => 'col-lg-5 no-padding',
                'filter_choice_css_class' => 'col-lg-6 no-padding',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
	    $resolver->setDefaults([
	    	'constraints'=> new Callback([
	    		'callback' => [$this, 'dateRangeCheck']
		    ])
	    ]);
    }

    public function dateRangeCheck($data, ExecutionContextInterface $context)
    {
	    $message = $this->translator->trans('search.period_start_superior_end_error',[], 'AppBundle');

		if($data['startDate'] > $data['endDate'] ){
			$context->addViolation($message);
		}
    }

	public function getBlockPrefix()
	{
		return 'search';
	}
}
