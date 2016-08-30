<?php

namespace FOS\UserBundle\Tests\Form\Type;

use FOS\UserBundle\Form\Type\ResettingFormType;
use Tests\UserBundle\Model\TestUser;
use UserBundle\Form\ResettingType;

class ResettingFormTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit()
    {
        $user = new TestUser();

        $form = $this->factory->create(ResettingType::class, $user);
        $formData = array(
            'plainPassword' => array(
                'first'         => 'test',
                'second'        => 'test',
            )
        );
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());
        $this->assertEquals('test', $user->getPlainPassword());
    }

    protected function getTypes()
    {
        return array_merge(parent::getTypes(), array(
            new ResettingFormType('Tests\UserBundle\Model\TestUser'),
        ));
    }
}
