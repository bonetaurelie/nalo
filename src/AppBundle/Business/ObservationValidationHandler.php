<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 16/08/16
 * Time: 16:55
 */

namespace AppBundle\Business;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use AppBundle\Form\ValidateObservationType;
use AppBundle\Services\MailerTemplating;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class ObservationValidationHandler
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
	 * @var TranslatorInterface
	 */
	private $translator;

	private $fromMail;


	public function __construct(
		FormFactory $formFactory,
		MailerTemplating $mailer,
		Session $session,
		EntityManager $manager,
		PaginatorInterface $paginator,
		TranslatorInterface $translator,
		$fromMail
	){
		//récupération du service form.factory
		$this->formFactory = $formFactory;
		//récupération du service d'envoie d'e-mail personnalisé
		$this->mailer = $mailer;
		//récupération du service de message flash
		$this->flashBag = $session->getFlashBag();

		$this->session = $session;

		$this->em = $manager;

		$this->paginator = $paginator;

		$this->translator = $translator;

		//initialisation de l'e-mail d'envoie
		$this->fromMail = $fromMail;
	}


	/**
	 * Retourne la liste des observations à valider avec un système de pagination automatique
	 *
	 * @use KnpPaginatorBundle
	 * @param $request
	 * @return mixed
	 */
	public function getObservationsListValidate(Request $request)
	{
		$query = $this->em->getRepository('AppBundle:Observation')->getQueryByState(Observation::STATE_STANDBY);

		return $this->paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			Observation::DEFAULT_ITEMS_BY_PAGE/*limit per page*/
		);
	}

	public function getFormView()
	{
		$this->createForm();
		return $this->form->createView();
	}

	/**
	 * Traite le retour du formulaire et execute les fonctions annexes
	 *
	 * @param Request $request
	 * @param Observation $observation
	 * @return void
	 */
	public function voteTreatment(Request $request,Observation $observation)
	{
		$this->createForm();

		$this->form->handleRequest($request);

		//Vérification si le formulaire est valide ou non
		if (!$this->form->isSubmitted() || !$this->form->isValid()){ return false; }

		//On récupère le vote si l'utilisateur à cliquer sur le bouton valider ou sur le bouton rejeter
		$vote = $this->form->get('valid')->isClicked();

		//récupération des données du formulaire
		$validatation = $this->form->getData();

		if(true !== $this->saveVote($observation, $vote)){ return false; }

		$this->sendConfirmVoteMail($observation, $vote, $validatation['comment']);

		return true;
	}

	/**
	 *
	 * Change le status de l'observation en fonction du vote (validé ou refusé)
	 *
	 * @param Observation $observation
	 * @param bool $vote
	 * @return bool
	 */
	public function saveVote(Observation $observation, $vote)
	{
		if(true === $vote){
			$message = $this->translator-> trans('observations.validate.valid_message', array(), 'AppBundle');
			$state = Observation::STATE_VALIDATED;
		}
		else{
			$message = $this->translator->trans('observations.validate.refuse_message', array(), 'AppBundle');
			$state = Observation::STATE_REFUSED;
		}

		try{
			$observation->setState($state);

			$this->em->persist($observation);
			$this->em->flush();

			$this->flashBag->add('success', $message);

			return true;
		}
		catch(Exception $e){
			$this->flashBag->add('error', "Une erreur est intervenue, veuillez recommencer, si l'erreur persiste, veuillez contacter l'administrateur du site!");

			return false;
		}
	}

	/**
	 *
	 * Envoie un e-mail à l'auteur de l'observation pour lui notifier que son observation a été traitée
	 *
	 * @param Observation $observation
	 * @param bool $vote
	 * @param string $comment
	 */
	public function sendConfirmVoteMail(Observation $observation, $vote, $comment)
	{
		$subjectVariables = array(
			'datetime' => $observation->getDatetimeObservation(),
			'speciesName' => $observation->getSpecies()->getFrenchName(),
		);

		if(true === $vote){
			$subject = $this->translator->trans('observations.validate.subject_valid', $subjectVariables, 'AppBundle');
		}
		else{
			$subject = $this->translator->trans('observations.validate.subject_refuse', $subjectVariables, 'AppBundle');
		}

		$this->mailer->send(
			array(
				'observation'   => $observation,
				'vote'          => $vote,
				'comment'       => $comment
			),
			$subject,
			$this->fromMail,
			$observation->getAuthor()->getEmail(),
			':Observations/validation:emailConfirmVote.html.twig'
		);
	}

	/**
	 * Genère le formulaire
	 */
	private function createForm()
	{
		//si il est pas déjà instancié on le fait
		if(null === $this->form){
			$this->form = $this->formFactory->create(ValidateObservationType::class);
		}
	}

}