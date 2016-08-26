<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ObservationValidationController extends Controller
{
	/**
	 * @Route("/observations-a-valider", name="app_observations_validate")
	 * @Security("has_role('ROLE_PRO')")
	 */
	public function ObservationsValidateAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation_validation');
		return $this->render(':Observations/validation:ObservationsValidate.html.twig', array('pagination' => $observationHandler->getObservationsListValidate($request)));
	}

	/**
	 * @Route("/validation-observation/{id}", name="app_observation_validate", requirements={"id": "\d+"})
	 * @ParamConverter("observation", class="AppBundle:Observation")
	 * @Security("has_role('ROLE_PRO')")
	 */
	public function validateAction(Observation $observation)
	{
		$observationValidation = $this->get('app.business_observation_validation');

		return $this->render(':Observations/validation:validate.html.twig', array(
			'form' => $observationValidation->getFormView(),
			'observation' => $observation
		));
	}

	/**
	 * @Route("/observation/{id}/vote", name="app_observation_save_vote", requirements={"id": "\d+"})
	 * @ParamConverter("observation", class="AppBundle:Observation")
	 * @Security("has_role('ROLE_PRO')")
	 */
	public function saveVoteAction(Request $request, Observation $observation)
	{
		$observationValidation = $this->get('app.business_observation_validation');
		$verif = $observationValidation->voteTreatment($request, $observation);

		//S'il y a eu une erreur on redirige vers la page de validation de l'observation
		if(false === $verif){
			return $this->redirectToRoute('app_observation_validate', ['id' => $observation->getId()]);
		}
		//sinon on redirige vers la liste des observations à valider
		return $this->redirectToRoute('app_observations_validate');
	}
}
