<?php

namespace AppBundle\Controller;


use AppBundle\Business\ObservationHandler;
use AppBundle\Entity\Observation;
use AppBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ObservationController extends Controller
{

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

        $searchInfos = $observation->getResultats($request);

        return $this->render(':Observations:search.html.twig', array(
            'form' => $form->createView(),
            'resultats' => $searchInfos->resultats,
	        'gps' => $searchInfos->gps
        ));
    }

	/**
	 * @Route("/observation/{id}", name="app_observation_detail", requirements={"id": "\d+"})
	 * @ParamConverter("observation", class="AppBundle:Observation")
	 */
    public function detailAction(Observation $observation){
	    $obsHandler = $this->get('app.business_observation');

	    //Si l'observation n'est pas autorisée à s'afficher, nous mettons une erreur not found
	    if(false === $obsHandler->verifVisibility($observation)){
			throw new NotFoundHttpException();
	    }

	    return $this->render(':Observations:detail.html.twig', ['observation' => $observation]);
    }
}