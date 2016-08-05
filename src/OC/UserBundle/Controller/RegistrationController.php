<?php
namespace OC\UserBundle\Controller;

use OC\UserBundle\Business\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController
{
    /**
     * @Route("/inscription", name="oc_user_register")
     * @param Request $request
     * @return RedirectResponse
     */
    public function registerAction(Request $request)
    {
        $registration = $this->get('oc_user.business.registration');

        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        dump($confirmationEnabled);
        dump(true);
//
//        $process = $formHandler->process($confirmationEnabled);
//        if ($process) {
//            $user = $form->getData();
//
//            /*****************************************************
//             * Add new functionality (e.g. log the registration) *
//             *****************************************************/
//            $this->container->get('logger')->info(
//                sprintf('New user registration: %s', $user)
//            );
//
//            if ($confirmationEnabled) {
//                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
//                $route = 'fos_user_registration_check_email';
//            } else {
//                $this->authenticateUser($user);
//                $route = 'fos_user_registration_confirmed';
//            }
//
//            $this->setFlash('fos_user_success', 'registration.flash.user_created');
//            $url = $this->container->get('router')->generate($route);
//
//            return new RedirectResponse($url);
//        }
//
        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $registration->getFormView(),
        ));
    }
}