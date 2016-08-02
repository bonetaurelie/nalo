<?php
namespace Tests\AppBundle\Form\Type;

use OC\CoreBundle\Form\ContactType;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class ContactTypeTest extends TypeTestCase
{
    private $validator;

    protected function setUp()
    {
        parent::setUp();

        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        $formTypeExtension = new FormTypeValidatorExtension($validator);
        $coreExtension = new CoreExtension();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addExtension($coreExtension)
            ->addTypeExtension($formTypeExtension)
            ->getFormFactory();
    }

//    protected function getExtensions()
//    {
//        $this->validator = $this->getMock(
//            'Symfony\Component\Validator\Validator\ValidatorInterface'
//        );
//        $this->validator
//            ->method('validate')
//            ->will($this->returnValue(new ConstraintViolationList()));
//
//        return array(
//            new ValidatorExtension($this->validator),
//        );
//    }


    public function testSubmitValidData()
    {
        $formData = array(
            'prenom' => 'test',
            'nom' => 'test',
            'email' => 'test@test.fr',
            'message' => 'test message',
        );

        $form = $this->factory->create(ContactType::class);

//        $object = TestObject::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
