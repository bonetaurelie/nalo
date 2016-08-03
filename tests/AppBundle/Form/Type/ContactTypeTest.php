<?php
namespace Tests\AppBundle\Form\Type;

use OC\CoreBundle\Form\ContactType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class ContactTypeTest extends TypeTestCase
{
    private $entityManager;
    protected function setUp()
    {
        // mock any dependencies
        $this->entityManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        parent::setUp();
    }

    protected function getExtensions()
    {
        // create a type instance with the mocked dependencies
        $type = new ContactType($this->entityManager);
        return array(
// register the type instances with the PreloadedExtension
            new PreloadedExtension(array($type), array()),
        );
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'prenom' => 'test',
            'nom' => 'test',
            'email' => 'test@test.fr',
            'message' => 'test message',
        );

        $form = $this->factory->create(ContactType::class);


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
