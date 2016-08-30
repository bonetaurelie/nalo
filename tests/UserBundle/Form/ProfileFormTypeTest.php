<?php

namespace FOS\UserBundle\Tests\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Tests\UserBundle\Model\TestUser;
use UserBundle\Form\ProfileType;

class ProfileFormTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit()
    {
        $user = new TestUser();

        $form = $this->factory->create(ProfileType::class, $user);
        $formData = array(
            'firstName'      => 'bar',
            'lastName'       => 'jo',
            'email'          => 'john@doe.com',
	        'plainPassword'  => ''
        );
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());
        $this->assertEquals('john@doe.com', $user->getUsername());
        $this->assertEquals('john@doe.com', $user->getEmail());
        $this->assertEquals('bar', $user->getFirstName());
        $this->assertEquals('jo', $user->getLastName());
        $this->assertEquals('', $user->getPlainPassword());
    }

    protected function getTypes()
    {
        return array_merge(parent::getTypes(), array(
            new ProfileFormType('Tests\UserBundle\Model\TestUser'),
        ));
    }
}
