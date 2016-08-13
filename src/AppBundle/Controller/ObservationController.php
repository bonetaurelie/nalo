<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/08/16
 * Time: 17:16
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ObservationController extends Controller
{
	/**
	 * @Route("/mes-observations", name="app_observations_list")
	 * @Security("has_role('ROLE_USER')")
	 */
	function MyObservationsAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$dql = "SELECT o FROM AppBundle:Observation o where o.author = :author ORDER BY o.datetimeObservation DESC";
		$query = $em->createQuery($dql)
					->setParameter(':author', $this->getUser())
		;

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			10/*limit per page*/
		);

		return $this->render(':Observations:myObservations.html.twig', array('pagination' => $pagination));
	}

	/**
	 * @Route("/ajouter-une-observation", name="app_observations_add")
	 * @Security("has_role('ROLE_USER')")
	 */
	function addObservation(Request $request)
	{
		$observation = new Observation();

		$form = $this->createForm(ObservationType::class, $observation);

		$form->handleRequest($request);

		//Vérification si le formulaire est valide ou non
		if ($form->isSubmitted() && $form->isValid()) {
			//récupération des données du formulaire
			$observation = $form->getData();

			$observation->setAuthor($this->getUser());

			$em = $this->getDoctrine()->getManager();

			$em->persist($observation);

			$em->flush();

			return $this->redirectToRoute('app_observations_list');
		}

		return $this->render(':Observations:add.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/observations-a-valider", name="app_observations_validate")
	 * @Security("has_role('ROLE_USER')")
	 */
	function ObservationsValidate(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$dql = "SELECT o FROM AppBundle:Observation o where o.state = :state ORDER BY o.datetimeObservation ASC";
		$query = $em->createQuery($dql)
			->setParameter(':state', Observation::STATE_STANDBY)
		;

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			10/*limit per page*/
		);

		return $this->render(':Observations:ObservationsValidate.html.twig', array('pagination' => $pagination));
	}

}