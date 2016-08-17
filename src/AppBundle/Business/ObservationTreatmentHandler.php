<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 16/08/16
 * Time: 15:47
 */

namespace AppBundle\Business;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use AppBundle\Services\MailerTemplating;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Model\UserInterface;

class ObservationTreatmentHandler
{
	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var FormFactory
	 */
	private $formFactory;
	/**
	 * @var Form
	 */
	private $form;
	/**
	 * @var MailerTemplating
	 */
	private $mailer;
	/**
	 * @var FlashBag
	 */
	private $flashBag;
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var PaginatorInterface
	 */
	private $paginator;

	/**
	 * @var string
	 */
	private $fromMail;

	/**
	 * @var Observation
	 */
	private $originalObservation;


	public function __construct(FormFactory $formFactory, MailerTemplating $mailer,Session $session, EntityManager $manager, PaginatorInterface $paginator, $fromMail)
	{
		//récupération du service form.factory
		$this->formFactory = $formFactory;
		//récupération du service d'envoie d'e-mail personnalisé
		$this->mailer = $mailer;
		//récupération du service de message flash
		$this->flashBag = $session->getFlashBag();

		$this->session = $session;

		$this->em = $manager;

		$this->paginator = $paginator;

		//initialisation de l'e-mail d'envoie
		$this->fromMail = $fromMail;
	}

	/**
	 * Return la liste des observations à valider avec un système de pagination automatique
	 *
	 * @use KnpPaginatorBundle
	 * @param $request
	 * @return mixed
	 */
	public function getOwnObservationsList(UserInterface $user, Request $request)
	{
		$query = $this->em->getRepository('AppBundle:Observation')->getQueryByAuthor($user);

		return $this->paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			Observation::DEFAULT_ITEMS_BY_PAGE/*limit per page*/
		);
	}


	public function createForm(Observation $observation){
		//si il est pas déjà instancié on le fait
		if(null === $this->form){
			$this->form = $this->formFactory->create(ObservationType::class, $observation);
		}

		return $this->form;
	}

	public function createAddform()
	{
		$this->createForm(new Observation());
	}

	public function createEditform(Observation $observation)
	{
		dump($observation->getImages()->count());
		$this->originalObservation = $observation;

		$this->createForm($observation);
		$department = $this->em->getRepository('AppBundle:locality\Department')->find($observation->getLocality()->getDepartment());

		$this->form->get('department')->setData($department);
	}

	public function getFormView()
	{
		return $this->form->createView();
	}


	public function formTreatment(Request $request, UserInterface $user){
		$this->form->handleRequest($request);

		//Vérification si le formulaire est valide ou non
		if ($this->form->isSubmitted() && $this->form->isValid()) {
			//récupération des données du formulaire
			$observation = $this->form->getData();
//
//			$originalObs = $this->em->getRepository('AppBundle:Observation')->find($observation);
//
//			dump($this->originalObservation->getImages()->count());

//			foreach($originalObs->getImages() as $originImage){
//
//				dump($observation->getImages()->contains($originImage));
//
//				if(!$observation->getImages()->contains($originImage)){
//					$this->em->remove($originImage);
//				}
//			}

			foreach ($observation->getImages() as $image){
				$image->setObservation($observation);
			}

			try{
				$observation->setAuthor($user);

				$observation->setState(Observation::STATE_STANDBY);//Remet en validation si changement

				//Si l'utilisateur est un professionnel on valide directement l'observation
				if($user->hasRole('ROLE_PRO')){
					$observation->setState(Observation::STATE_VALIDATED);
				}

				$this->em->persist($observation);
				$this->em->flush();

				$this->flashBag->add('success', "L'observation a été enregistrée avec succès!");

				return true;
			}
			catch(Exception $e){
				$this->flashBag->add('error', "Une erreur est intervenue, veuillez recommencer, si l'erreur persiste, veuillez contacter l'administrateur du site!");

				return false;
			}

		}
	}
}