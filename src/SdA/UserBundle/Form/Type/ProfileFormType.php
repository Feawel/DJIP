<?php

namespace SdA\UserBundle\Form\Type;


use SdA\UserBundle\Entity\User;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		// add your custom field
		$builder
        		->add('coverPicture', 'sonata_media_type', array(
        				'provider' => 'sonata.media.provider.image',
        				'context'  => 'default',
        				'required'  => false,
        		))
        		->add('profilePicture', 'sonata_media_type', array(
        				'provider' => 'sonata.media.provider.image',
        				'context'  => 'default',
        				'required'  => false,
        		))
		;
	}

	public function getName()
	{
		return 'sda_user_profile';
	}
}