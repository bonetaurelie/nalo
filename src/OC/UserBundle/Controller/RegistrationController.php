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
        $submit = $registration->formTreatment($request);

        if(true === $submit){
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $registration->getFormView(),
        ));
    }
}