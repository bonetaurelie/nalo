<?php

namespace AppBundle\Business;


use AppBundle\Entity\Observation;
use AppBundle\Form\SearchType;
use AppBundle\Services\MailerTemplating;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

class ObservationHandler
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


	private $fromMail;

    /**
     * @var Form
     */
    private $searchForm;

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
	 * Retourne les dernières observations validées
	 * @param int $nbItems
	 * @return \AppBundle\Entity\Observation[]|array
	 */
	public function getLastObservationsValidateList($nbItems= 4){
		return $this->em->getRepository('AppBundle:Observation')->findBy(
			array('state' => Observation::STATE_VALIDATED),
			array('datetimeObservation' => 'DESC'),
			$nbItems
		);
	}

	public function initForm()
    {
        $this->searchForm =  $this->formFactory->create(SearchType::class);
    }

	/**
	 * Initialise le formulaire et le renvoie
	 *
	 * @return Form|\Symfony\Component\Form\FormInterface
	 */
	public function getSearchForm()
    {
        if(null === $this->searchForm){
            $this->initForm();
        }

        return $this->searchForm;
    }

    /**
     * Initialise aux valeurs par le formulaire de recherche
     */
    public function resetSearchData()
    {
        $this->verifInitSeachForm();//verifie que le formulaire soit bien initialisé

        $this->searchForm->get('startDate')->setData(new \DateTime());
        $this->searchForm->get('endDate')->setData(new \DateTime());
        $this->session->set('search', null);
    }

    /**
     * Traite la validation du formulaire
     *
     * @param Request $request
     */
    public function searchFormTreatment(Request $request)
    {
        $this->verifInitSeachForm();//verifie que le formulaire soit bien initialisé

        $this->searchForm->handleRequest($request);

        if(!$this->searchForm->isSubmitted() || !$this->searchForm->isValid()){
             return false;
        }

        $this->setSearchFormDataToSession($this->searchForm->getData());

        return true;
    }

    /**
     * Traite les données du formulaire
     *
     * @param $formData
     */
    public function dataTreatment($formData)
    {
        $data = (object) $formData;

        if(null !==  $data->department){
            $department =  $this->em->getRepository('AppBundle:locality\Department')->find($data->department->getId());
            $data->department = $department;
        }

        if(null !==  $data->city){
            $city =  $this->em->getRepository('AppBundle:locality\City')->find($data->city->getId());
            $data->city = $city;
        }

        if(null !==  $data->species){
            $species =  $this->em->getRepository('AppBundle:Species')->find($data->species->getId());
            $data->species = $species;
        }

        return $data;
    }


	/**
	 * Met en session les données recherchées
	 *
	 * @param data
	 * @return void
	 */
    public function setSearchFormDataToSession($data)
    {
	    $this->session->set('search', $data);
	}

    /**
     * Récupère les données recherchées en session
     *
     * @return mixed
     */
    public function getSearchFormDataToSession()
    {
    	$search = $this->session->get('search');

    	if(null === $search){
    		return null;
    	}


        $data = $this->dataTreatment($search);

        if(!$this->searchForm->isSubmitted()){
            $this->hydrateFormFields($data);//si on passe de page en page dans les résultats pour garder les données des champs du formulaire intact
        }

        return $data;
    }

	/**
	 *
	 * Initialise les champs du formulaire avec les infos qui ont été recherchées
	 *
	 * @param $data
	 */
	public function hydrateFormFields($data)
	{
        $this->verifInitSeachForm();//verifie que le formulaire soit bien initialisé

		$this->searchForm->get('startDate')->setData($data->startDate);
		$this->searchForm->get('endDate')->setData($data->endDate);
		$this->searchForm->get('department')->setData($data->department);
		$this->searchForm->get('city')->setData($data->city);
		$this->searchForm->get('species')->setData($data->species);
	}

	/**
	 * Récupère les résultats de la recherche si présent
	 *
	 * @param Request $request
	 * @return object
	 */
	public function getResultats(Request $request)
    {
        $query = $this->getSearchQuery();

	    if(null === $query){
		    return null;
	    }

        return $this->paginator->paginate(
	        $query,
            $request->query->getInt('page', 1),
	        Observation::DEFAULT_ITEMS_BY_PAGE
        );
    }

    /**
     * Récupération de la requête de recherche
     *
     * @return \Doctrine\ORM\QueryBuilder|null
     */
    public function getSearchQuery()
    {
        $search = $this->getSearchFormDataToSession();

        if(null === $search){
            return null;
        }

        return $this->em->getRepository('AppBundle:Observation')->search(
            $search->startDate,
            $search->endDate,
            $search->department,
            $search->city,
            $search->species
        );
    }

    /**
     * Vérifie si l'observation peut-être affiché (si elle est valide)
     *
     * @param Observation $observation
     * @return bool
     */
    public function verifVisibility(Observation $observation)
    {
		return $observation->getState() === Observation::STATE_VALIDATED;
    }

    /**
     * Vérifie si le formulaire est instancié
     *
     * @throws \Exception
     */
    private function verifInitSeachForm()
    {
        if(null === $this->searchForm){
            throw new \Exception("Use getSearchForm before!");
        }
    }
}