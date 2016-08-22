<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/08/16
 * Time: 16:25
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
	private $container;


	public function load(ObjectManager $manager)
	{
		$userManager = $this->container->get('fos_user.user_manager');

		$fakeUsers = $this->getFakeUsersList();

		foreach ($fakeUsers as $fakeUser){
			$user = $userManager->createUser();

			$user
				->setFirstName($fakeUser->firstName)
				->setLastName($fakeUser->lastName)
				->setEmail($fakeUser->email)
				->setRoles(array($fakeUser->role))
				->setEnabled(true)
				->setPlainPassword($fakeUser->plainpassword)
			;

			$userManager->updateUser($user);
		}
	}

	public function getFakeUsersList(){
		$fakeUsers = array();

		$fakeUsers[] = (object) array(
			'firstName' => 'Amateur',
			'lastName'  => 'TEST',
			'email'     => 'test.amateur@test.fr',
			'plainpassword' => 'test1A-',
			'role' => 'ROLE_USER'
		);

		$fakeUsers[] = (object) array(
			'firstName' => 'Professionnel',
			'lastName'  => 'TEST',
			'email'     => 'test.professionnel@test.fr',
			'plainpassword' => 'test1P-',
			'role' => 'ROLE_PRO'
		);

		return $fakeUsers;
	}

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}