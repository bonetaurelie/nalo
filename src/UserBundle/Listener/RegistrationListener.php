<?php

namespace UserBundle\Listener;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class RegistrationListener
{

	private $router;

	/**
	 * @var AuthorizationChecker
	 */
	private $authorization;

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	public function __construct(UrlGeneratorInterface $router, AuthorizationChecker $authorization, TokenStorageInterface $tokenStorage)
	{
		$this->router   = $router;
		$this->authorization = $authorization;

		$this->tokenStorage = $tokenStorage;
	}

	public function onRouteToRegistration(FilterResponseEvent $event)
	{

		//si on est pas sur la requete principal on fait rien
		if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
			return;
		}

		//si l'utilisateur n'est pas authentifié on ne fait rien
		if(!$this->tokenStorage->getToken()){
			return;
		}

		$currentRoute = $event->getRequest()->attributes->get('_route');

		if('fos_user_registration_register' === $currentRoute && $this->authorization->isGranted('ROLE_USER')){
			$url = $this->router->generate('fos_user_profile_edit');
			$event->setResponse(new RedirectResponse($url));
		}

	}
}