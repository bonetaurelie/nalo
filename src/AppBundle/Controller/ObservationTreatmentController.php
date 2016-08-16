<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Form\ValidateObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ObservationTreatmentController extends Controller
{
	/**
	 * @Route("/mes-observations", name="app_observations_list")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function MyObservationsAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation_treatment');

		$listPaginated =  $observationHandler->getOwnObservationsList($this->getUser(), $request);

		return $this->render(':Observations/treatment:myObservations.html.twig', array('pagination' => $listPaginated));
	}


	/**
	 * @Route("/ajouter-une-observation", name="app_observations_add")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function addObservationAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation_treatment');
		$observationHandler->createAddform();

		//Si l'enregistrement est OK on redirige vers la lise des observations
		if(true === $observationHandler->formTreatment($request, $this->getUser())){
			return $this->redirectToRoute('app_observations_list');
		}

		return $this->render(':Observations/treatment:add.html.twig', array(
			'form' => $observationHandler->getFormView()
		));
	}

	/**
	 * @Route("/edit-une-observation/{id}", name="app_observations_edit", requirements={"id": "\d+"})
	 * @ParamConverter("observation", class="AppBundle:Observation")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function editObservationAction(Request $request, Observation $observation)
	{
		$observationHandler = $this->get('app.business_observation_treatment');
		$observationHandler->createEditform($observation);

		//Si l'enregistrement est OK on redirige vers la lise des observations
		if(true === $observationHandler->formTreatment($request, $this->getUser())){
			return $this->redirectToRoute('app_observations_list');
		}

		return $this->render(':Observations/treatment:edit.html.twig', array(
			'form' => $observationHandler->getFormView()
		));
	}
}