<?php

namespace OC\UserBundle\Subscriber;



use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class ProfileEditSubscriber implements EventSubscriberInterface
{

	private $router;

	public function __construct(UrlGeneratorInterface $router)
	{
		$this->router = $router;
	}

	public static function getSubscribedEvents()
	{
		return [FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditSuccess'];
	}


	public function onProfileEditSuccess(FormEvent $event){
		$url = $this->router->generate('fos_user_profile_edit');
		$event->setResponse(new RedirectResponse($url));
	}
}