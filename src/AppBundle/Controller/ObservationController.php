<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/08/16
 * Time: 17:16
 */

namespace AppBundle\Controller;


use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ObservationController extends Controller
{
	/**
	 * @Route("/mes-observations", name="app_observations_list")
	 * @Security("has_role('ROLE_USER')")
	 */
	function MyObservationsAction()
	{
		return $this->render(':Observations:myObservations.html.twig');
	}
	/**
	 * @Route("/mes-observations", name="app_observations_list")
	 * @Security("has_role('ROLE_USER')")
	 */
	function addObservation()
	{
		$form = $this->createForm(ObservationType::class);

		return $this->render(':Observations:add.html.twig', array(
			'form' => $form->createView()
		));
	}
}