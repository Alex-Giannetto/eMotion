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

        return $vehicle->getDailyPrice() * ++$dayCount;
    }

    public function getPriceWithPromotionForDate(Vehicle $vehicle, \DateTime $start, \DateTime $end)
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


}