<?php


namespace App\Service;


use App\Entity\Rental;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use DateTime;

class RentalService
{
    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;


    /**
     * RentalService constructor.
     */
    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function getPriceForDate(Vehicle $vehicle, DateTime $start, DateTime $end)
    {

        $dayCount = $start->diff($end)->format("%a");

        return $this->getPrice($vehicle, $dayCount);
    }

    public function getPrice(Vehicle $vehicle, int $dayCount): float
    {

        return $vehicle->getDailyPrice() * ++$dayCount;
    }

    public function getPriceWithPromotionForDate(Vehicle $vehicle, DateTime $start, DateTime $end)
    {

        $dayCount = $start->diff($end)->format("%a");

        return $this->getPriceWithPromotion($vehicle, $dayCount);
    }

    public function getPriceWithPromotion(Vehicle $vehicle, int $dayCount): float
    {
        $minimalDailyCarPrice = ($vehicle->getMinDailyPrice() * ($dayCount < 180 ? 1.5 : 1.25));
        $prices[] = $vehicle->getDailyPrice();

        for ($i = 1; $i <= $dayCount; ++$i) {
            $price = $prices[count($prices) - 1] * .98;
            $prices[] = $price >= $minimalDailyCarPrice ? $price : $minimalDailyCarPrice;
        }

        return round(array_sum($prices), 2);
    }

    public function rentalIsPossible(Rental $rental): bool
    {
        return !in_array(
            $rental->getVehicle(),

            $this->vehicleRepository->getAvailableVehicle(
                $rental->getVehicle()->getId(),
                $rental->getVehicle()->getCarDealer()->getId(),
                $rental->getStartRentalDate(),
                $rental->getEstimatedReturnDate()
            )
        );
    }


}