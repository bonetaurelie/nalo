<?php

namespace OC\UserBundle\Business;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManager;
use OC\UserBundle\Form\RegistrationType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class Registration
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \FOS\UserBundle\Model\UserInterface
     */
    private $user;

    /**
     * Registration constructor.
     * @param FormFactory $formFactory
     * @param UserManager $userManager
     * @param EventDispatcherInterface $dispatcher
     * @param Session $session
     * @param Router $router
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        FormFactory $formFactory,
        UserManager $userManager,
        EventDispatcherInterface $dispatcher,
        Session $session,
        Router $router,
        TokenStorage $tokenStorage
    ){
        $this->userManager = $userManager;
        $this->dispatcher = $dispatcher;
        $this->session = $session;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;

        $this->user = $this->userManager->createUser();

        $request = Request::createFromGlobals();
        $event = new GetResponseUserEvent( $this->user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        $this->form = $formFactory->create(RegistrationType::class, $this->user);
    }

    public function getFormView()
    {
        return $this->form->createView();
    }


    /**
     * Traite les données du formulaire, si tout est valide retourne true
     * @param Request $request
     * @return bool
     */
    public function formTreatment(Request $request)
    {
        $this->form->handleRequest($request);

        //Vérification si le formulaire est valide ou non
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $event = new FormEvent($this->form, $request);
            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            if(true === $this->user->getRolePro()){
                $this->user->setRoles(array('ROLE_PRO'));
            }

            //récupération des données du formulaire
            $this->userManager->updateUser($this->user);

            $this->login();

            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($this->user, $request, new Response()));
//            if (null === $response = $event->getResponse()) {
//                $url =  $this->router->generate('oc_core_homepage');
//                $response = new RedirectResponse($url);
//            }
//            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($this->user, $request, $response));
//
//            return $response;
            return true;
        }

        return false;
    }

    private function login()
    {
        $token = new UsernamePasswordToken($this->user, null, 'main', $this->user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));
    }
}