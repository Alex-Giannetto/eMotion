<?php

namespace App\Controller;

use App\Entity\CarDealer;
use App\Entity\Rental;
use App\Entity\Vehicle;
use App\Repository\CarDealerRepository;
use App\Repository\VehicleRepository;
use App\Repository\VehicleTypeRepository;
use App\Service\RentalService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentalController extends AbstractController
{
    /**
     * @Route("/search", name="rental__search")
     * @param Request $request
     * @param VehicleRepository $vehicleRepository
     * @param VehicleTypeRepository $vehicleTypeRepository
     * @param CarDealerRepository $carDealerRepository
     * @param RentalService $rentalService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request, VehicleRepository $vehicleRepository, VehicleTypeRepository $vehicleTypeRepository, CarDealerRepository $carDealerRepository)
    {

        $rentalService = new RentalService($vehicleRepository);

        $dateStart = DateTime::createFromFormat('d/m/Y', $request->query->get('date_start'));
        $dateEnd = DateTime::createFromFormat('d/m/Y', $request->query->get('date_end'));


        $idTypeVehicle = $request->query->get('type');
        $idLocation = $request->query->get('location');

        $vehicles = $vehicleRepository->getAvailableVehicle($idTypeVehicle, $idLocation, $dateStart, $dateEnd);

        return $this->render('rental/index.html.twig', [
            'vehicles' => $vehicles,
            'carDealer' => $carDealerRepository->findAll(),
            'carType' => $vehicleTypeRepository->findAll(),
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'idLocation' => $idLocation,
            'idTypeVehicle' => $idTypeVehicle,
            'rentalService' => $rentalService,

        ]);
    }


    /**
     * @Route("/overview/{dateStart}/{dateEnd}/{carDealer}/{vehicle}", name="rental__overview")
     * @ParamConverter("carDealer", options={"id" = "carDealer"})
     * @ParamConverter("vehicle", options={"id" = "vehicle"})
     */
    public function overview(string $dateStart, string $dateEnd, CarDealer $carDealer, Vehicle $vehicle, VehicleRepository $vehicleRepository)
    {

        $rentalService = new RentalService($vehicleRepository);

        $dateStart = DateTime::createFromFormat('Y-m-d', $dateStart);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $dateEnd);


        if ($dateStart && $dateEnd && ($dateStart < $dateEnd)) {
            $this->redirectToRoute('home');
        }

        $availableVehicleForSelectedDate = $vehicleRepository->getAvailableVehicle(
            $vehicle->getVehicleType()->getId(),
            $carDealer->getId(),
            $dateStart,
            $dateEnd
        );

        if (!in_array($vehicle, $availableVehicleForSelectedDate)) {
            return $this->redirectToRoute('home');
        }

        $rental = new Rental();
        $rental->setClient($this->getUser());
        $rental->setVehicle($vehicle);
        $rental->setStartRentalDate($dateStart);
        $rental->setEstimatedReturnDate($dateEnd);
        $rental->setPrice($vehicle->getDailyPrice());

        return $this->render('rental/overview.html.twig', [
            'rental' => $rental,
            'rentalService' => $rentalService,
        ]);

    }


    /**
     * @Route("/book/{dateStart}/{dateEnd}/{vehicle}", name="rental__book")
     * @ParamConverter("vehicle", options={"id" = "vehicle"})
     * @IsGranted("ROLE_USER")
     */
    public function book(string $dateStart, string $dateEnd, Vehicle $vehicle, Request $request, VehicleRepository $vehicleRepository, EntityManagerInterface $entityManager)
    {

        $rentalService = new RentalService($vehicleRepository);

        $rental = new Rental();
        $rental->setClient($this->getUser());
        $rental->setVehicle($vehicle);
        $rental->setStartRentalDate(new DateTime($dateStart));
        $rental->setEstimatedReturnDate(new DateTime($dateEnd));
        $rental->setPrice($rentalService->getPriceWithPromotionForDate($vehicle, $rental->getStartRentalDate(), $rental->getEstimatedReturnDate()));

        if (!$rentalService->rentalIsPossible($rental)) {
            $this->redirectToRoute('home');
        }


        $form = $this->createFormBuilder()
            ->add('cgl', CheckboxType::class, [
                'label' => 'Je certifie accepter les condition générale de location disponible à cette adresse',
                'required' => true,
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Réserver'
            ])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->getData()['cgl']) {
            $entityManager->persist($rental);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation à bien été enregistré');
        }


        return $this->render('rental/book.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);

    }
}
