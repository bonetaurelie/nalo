<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('alt')
			->add('file', VichImageType::class, array(
				'required' => false,
				'allow_delete' => false,
				'download_link' => false,
			))
		;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
	    $resolver->setDefaults(array(
		    'data_class' => 'AppBundle\Entity\Image',
	    ));
    }

    public function getName()
    {
        return 'app_bundle_image_type';
    }
}
