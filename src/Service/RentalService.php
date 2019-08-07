<?php


namespace App\Service;


use App\Entity\Vehicle;

class RentalService
{
    public function getPriceForDate(Vehicle $vehicle, \DateTime $start, \DateTime $end)
    {

        $dayCount = $start->diff($end)->format("%a");

        return $this->getPrice($vehicle, $dayCount);
    }

    public function getPrice(Vehicle $vehicle, int $dayCount): float
    {
        $minimalDailyCarPrice = ($vehicle->getMinDailyPrice() * ($dayCount < 180 ? 1.5 : 1.25));
        $prices[] = $vehicle->getDailyPrice();

        for ($i = 0; $i < $dayCount; ++$i) {
            $price = $prices[count($prices) - 1] * .985;
            $prices[] = $price >= $minimalDailyCarPrice ? $price : $minimalDailyCarPrice;
        }

        return round(array_sum($prices), 2);
    }

}