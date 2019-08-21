<?php

namespace App\Controller;

use App\Entity\CarDealer;
use App\Entity\Rental;
use App\Entity\Vehicle;
use App\Entity\VehicleType;
use App\Repository\VehicleRepository;
use App\Service\MailService;
use App\Service\PDFService;
use App\Service\RentalService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentalController extends AbstractController
{
    /**
     * @var PDFService
     */
    private $PDFService;
    /**
     * @var RentalService
     */
    private $rentalService;
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * RentalController constructor.
     */
    public function __construct(
        PDFService $PDFService,
        RentalService $rentalService,
        MailService $mailService
    ) {
        $this->PDFService = $PDFService;
        $this->rentalService = $rentalService;
        $this->mailService = $mailService;
    }


    /**
     * @Route("/search/{dateStart}/{dateEnd}/{carDealer}/{vehicleType}", name="rental__search")
     * @ParamConverter("carDealer", options={"id" = "carDealer"})
     * @ParamConverter("vehicleType", options={"id" = "vehicleType"})
     */
    public function search(
        string $dateStart,
        string $dateEnd,
        CarDealer $carDealer,
        VehicleType $vehicleType,
        Request $request,
        VehicleRepository $vehicleRepository
    ) {

        $dateStart = DateTime::createFromFormat('Y-m-d', $dateStart);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $dateEnd);
        $dateActual = DateTime::createFromFormat('Y-m-d' , date('Y-m-d'));

        $form = $this->createFormBuilder()
            ->add('location', EntityType::class, [
                'class' => CarDealer::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Lieux',
                'data' => $carDealer,
            ])
            ->add('start', null, [
                'attr' => ['class' => 'js-datepicker'],
                'required' => true,
                'label' => 'Début',
                'data' => $dateStart->format('d/m/Y'),
            ])
            ->add('end', null, [
                'attr' => ['class' => 'js-datepicker'],
                'required' => true,
                'label' => 'Fin',
                'data' => $dateEnd->format('d/m/Y'),
            ])
            ->add('type', EntityType::class, [
                'class' => VehicleType::class,
                'choice_label' => 'label',
                'required' => true,
                'label' => 'Type',
                'data' => $vehicleType,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Chercher',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('rental__search', [
                'carDealer' => $form->getData()['location']->getId(),
                'dateStart' => DateTime::createFromFormat('d/m/Y',
                    $form->getData()['start'])->format('Y-m-d'),
                'dateEnd' => DateTime::createFromFormat('d/m/Y',
                    $form->getData()['end'])->format('Y-m-d'),
                'vehicleType' => $form->getData()['type']->getId(),
            ]);
        }
        if ($dateStart < $dateActual || $dateEnd < $dateActual) {
            $this->addFlash('danger', 'Les dates sont inférieures à la date d\'aujourd\'hui');
            return $this->redirectToRoute('home');
        }

        if (!$dateStart || !$dateEnd || ($dateStart > $dateEnd)) {
            $this->addFlash('danger', 'Veuillez renseigner des dates valides');

            return $this->redirectToRoute('home');
        }

        $vehicles = $vehicleRepository->getAvailableVehicles($vehicleType->getId(),
            $carDealer->getId(), $dateStart, $dateEnd);

        return $this->render('rental/index.html.twig', [
            'form' => $form->createView(),
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'vehicles' => $vehicles,
            'rentalService' => $this->rentalService,
        ]);
    }


    /**
     * @Route("/overview/{dateStart}/{dateEnd}/{carDealer}/{vehicle}", name="rental__overview")
     * @ParamConverter("carDealer", options={"id" = "carDealer"})
     * @ParamConverter("vehicle", options={"id" = "vehicle"})
     */
    public function overview(
        string $dateStart,
        string $dateEnd,
        Vehicle $vehicle
    ) {

        $dateStart = DateTime::createFromFormat('Y-m-d', $dateStart);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $dateEnd);
        $dateActual = DateTime::createFromFormat('Y-m-d' , date('Y-m-d'));

        if (!$dateStart && !$dateEnd || ($dateStart > $dateEnd)) {
            $this->addFlash('danger', 'dates sont incorrectes');
            return $this->redirectToRoute('home');
        }
        if ($dateStart < $dateActual || $dateEnd < $dateActual) {
            $this->addFlash('danger', 'Les dates sont inférieures à la date d\'aujourd\'hui');
            return $this->redirectToRoute('home');
        }

        $rental = new Rental();
        $rental->setClient($this->getUser());
        $rental->setVehicle($vehicle);
        $rental->setStartRentalDate($dateStart);
        $rental->setEstimatedReturnDate($dateEnd);
        $rental->setPrice($this->rentalService->getPriceForDate($vehicle,
            $dateStart,
            $dateEnd));


        if (!$this->rentalService->rentalIsPossible($rental)) {
            $this->addFlash('danger',
                'Le véhicle n\'est pas disponible aux dates renseignés ');

            return $this->redirectToRoute('home');
        }

        return $this->render('rental/overview.html.twig', [
            'rental' => $rental,
            'rentalService' => $this->rentalService,
        ]);

    }


    /**
     * @Route("/book/{dateStart}/{dateEnd}/{vehicle}", name="rental__book")
     * @ParamConverter("vehicle", options={"id" = "vehicle"})
     * @IsGranted("ROLE_USER")
     */
    public function book(
        string $dateStart,
        string $dateEnd,
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $entityManager,
        MailService $mailService
    ) {


        $dateStart = DateTime::createFromFormat('Y-m-d', $dateStart);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $dateEnd);
        $dateActual = DateTime::createFromFormat('Y-m-d' , date('Y-m-d'));
        if (!$dateStart && !$dateEnd || ($dateStart > $dateEnd)) {
            $this->addFlash('danger', 'dates sont incorrectes');
            return $this->redirectToRoute('home');
        }
        if ($dateStart < $dateActual || $dateEnd < $dateActual) {
            $this->addFlash('danger', 'Les dates sont inférieures à la date d\'aujourd\'hui');
            return $this->redirectToRoute('home');
        }

        $rental = new Rental();
        $rental->setClient($this->getUser());
        $rental->setVehicle($vehicle);
        $rental->setStartRentalDate($dateStart);
        $rental->setEstimatedReturnDate($dateEnd);
        $rental->setPrice($this->rentalService->getPriceWithPromotionForDate($vehicle,
            $dateStart,
            $dateEnd));


        if (!$this->rentalService->rentalIsPossible($rental)) {
            $this->addFlash('danger',
                'Le véhicle n\'est pas disponible aux dates renseignés');

            return $this->redirectToRoute('home');
        }


        $form = $this->createFormBuilder()
            ->add('city', null, [
                'label' => 'lieux de la signature',
                'required' => true,
            ])
            ->add('signature', HiddenType::class)
            ->add('cgl', CheckboxType::class, [
                'label' => 'Je certifie accepter les conditions générales de location disponible à cette adresse',
                'required' => true,
            ])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->getData()['cgl']) {

            $entityManager->persist($rental);
            $entityManager->flush();

            $city = $form->getData()['city'];
            $signature = $form->getData()['signature'];

            $rental = $this->PDFService->generateContract($rental, $city,
                $signature);

            $this->addFlash(
                'success',
                'Votre réservation à bien été enregistré. Vous retrouverez votre contrat par mail'
            );

            $entityManager->persist($rental);
            $entityManager->flush();

            $this->mailService->sendMailContrat($rental);

            return $this->redirectToRoute('home');
        }

        return $this->render('rental/book.html.twig', [
            'rental' => $rental,
            'form' => $form->createView(),
        ]);

    }
}
