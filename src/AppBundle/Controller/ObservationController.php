<?php

namespace AppBundle\Controller;


use AppBundle\Business\ObservationService;
use AppBundle\Entity\Observation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ObservationController extends Controller
{
	/**
	 * @Route("/ajouter-une-observation", name="app_observations_add")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function addObservationAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation');
		$observationHandler->createAddform();

		//Si l'enregistrement est OK on redirige vers la lise des observations
		if(true === $observationHandler->formTreatment($request, $this->getUser())){
			return $this->redirectToRoute('app_observations_list');
		}

		return $this->render(':Observations:add.html.twig', array(
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
		$observationHandler = $this->get('app.business_observation');
		$observationHandler->createEditform($observation);

		//Si l'enregistrement est OK on redirige vers la lise des observations
		if(true === $observationHandler->formTreatment($request, $this->getUser())){
			return $this->redirectToRoute('app_observations_list');
		}

		return $this->render(':Observations:add.html.twig', array(
			'form' => $observationHandler->getFormView()
		));
	}

	/**
	 * @Route("/mes-observations", name="app_observations_list")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function MyObservationsAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation');

		$listPaginated =  $observationHandler->getOwnObservationsList($this->getUser(), $request);

		return $this->render(':Observations:myObservations.html.twig', array('pagination' => $listPaginated));
	}

	/**
	 * @Route("/observations-a-valider", name="app_observations_validate")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function ObservationsValidateAction(Request $request)
	{
		$observationHandler = $this->get('app.business_observation');
		return $this->render(':Observations:ObservationsValidate.html.twig', array('pagination' => $observationHandler->getObservationsListValidate($request)));
	}

	public function lastObservationsValidateAction($nbItems = 4){
		$observationHandler = $this->get('app.business_observation');
		$lastObservations = $observationHandler->getLastObservationsValidateList($nbItems);

		return $this->render(':Observations:list.html.twig', ['observations' => $lastObservations]);
	}

    /**
     * @param Request $request
     * @Route("/recherche", name="app_observations_search")
     */
	public function search(Request $request)
    {
        $observation = $this->get('app.business_observation');
        $form = $observation->getSearchForm();

        $resultats = $observation->getResultats($request);

        return $this->render(':Observations:search.html.twig', array(
            'form' => $form->createView(),
            'resultats' => $resultats
        ));
    }
}