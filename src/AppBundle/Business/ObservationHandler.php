<?php

namespace AppBundle\Business;


use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use AppBundle\Form\RechercheType;
use AppBundle\Services\MailerTemplating;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
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

	public function getSearchForm()
    {
        $this->searchForm =  $this->formFactory->create(RechercheType::class);
        return $this->searchForm;
    }

    private function setSearchFormDataToSession(Request $request)
    {
	    if(null === $this->searchForm){
		    throw new \Exception("Use getSearchForm before!");
	    }

	    $this->searchForm->handleRequest($request);

	    if(!$this->searchForm->isSubmitted() || !$this->searchForm->isValid()){
			return false;

	    }
	    $search = (object) $this->searchForm->getData();

	    $this->session->set('search', $search);

	    $city = $this->em->getRepository('AppBundle:locality\City')->find($search->city);
	    $species = $this->em->getRepository('AppBundle:Species')->find( $search->species);

		$search->city = $city;
		$search->species = $species;

	    $this->session->set('search', $search);
	}

	public function setSearchFormDataToDisplay($data)
	{
		$department = $this->em->getRepository('AppBundle:locality\Department')->find($data->department->getId());
		$city = $this->em->getRepository('AppBundle:locality\City')->find($data->city);
		$species = $this->em->getRepository('AppBundle:Species')->find( $data->species);

		$this->searchForm->get('startDate')->setData($data->startDate);
		$this->searchForm->get('endDate')->setData($data->endDate);
		$this->searchForm->get('department')->setData($department);
		$this->searchForm->get('city')->setData($city);
		$this->searchForm->get('species')->setData($species);
	}

	public function getResultats(Request $request)
    {
    	//On charge les données du formulaire
	    $verifSubmit = $this->setSearchFormDataToSession($request);

		$search = $this->session->get('search');

	    if(null === $search){
		    return (object) array(
			    'resultats' => null,
			    'gps'      => null
		    ) ;
	    }

	    if(false === $verifSubmit && null !== $request->get('page')){
		    $this->setSearchFormDataToDisplay($search);
	    }


        $query = $this->em->getRepository('AppBundle:Observation')->search(
            $search->startDate,
            $search->endDate,
	        $search->city,
	        $search->species
        );

        $resultats = $this->paginator->paginate(
	        $query,
	        $request->query->getInt('page', 1),
	        Observation::DEFAULT_ITEMS_BY_PAGE
        );

	    dump($query);

        return (object) array(
            'resultats' => $resultats,
            'gps'      => (object) array(
                'latitude' => $search->city->getLatitude(),
	            'longitude' => $search->city->getLongitude()
            )
        ) ;
    }

    public function verifVisibility(Observation $observation)
    {
		return $observation->getState() === Observation::STATE_VALIDATED;
    }
}