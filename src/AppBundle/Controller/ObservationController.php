<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ObservationController extends Controller
{

    /**
     * Affiche la liste des dernières observations
     *
     * @param int $nbItems
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function lastObservationsValidateAction($nbItems = 4){
		$observationHandler = $this->get('app.business_observation');
		$lastObservations = $observationHandler->getLastObservationsValidateList($nbItems);

		return $this->render(':Observations:list.html.twig', ['observations' => $lastObservations]);
	}

    /**
     * Page de recherche
     * Affiche seulement le formulaire
     *
     * @Route("/recherche", name="app_observations_search")
     */
	public function searchAction()
    {
        $observation = $this->get('app.business_observation');
        $form = $observation->getSearchForm();
        // Si une recherche a déjà été effectuée on vide le cache de recherche
        // et on met les valeurs par défaut du formulaire
        $observation->resetSearchData();

        return $this->render(':Observations:search.html.twig', array(
            'form' => $form->createView(),
            'results' => null
        ));
    }

    /**
     * Page de résultats, affiche le formulaire avec les champs préremplis en fonction des données de recherche
     * et affiche bien sûr les résultats avec une pagination et une carte
     *
     * @param Request $request
     * @Route("/resultats", name="app_observations_results")
     */
    public function resultsAction(Request $request)
    {
        $observation = $this->get('app.business_observation');
        $form = $observation->getSearchForm();
        $observation->searchFormTreatment($request);
        $results = $observation->getResultats($request);

        return $this->render(':Observations:search.html.twig', array(
            'form' => $form->createView(),
            'results' => $results
        ));
    }

	/**
     * Affiche la fiche d'une observation
     *
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