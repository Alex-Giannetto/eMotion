<?php


namespace App\DataFixtures;


use App\Entity\CarDealer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CarDealerFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        foreach ($this->getCarDealers() as $carDealerInfos) {
            $carDealer = new CarDealer();
            $carDealer->setName($carDealerInfos['name']);
            $carDealer->setAddress($carDealerInfos['address']);
            $carDealer->setCity($carDealerInfos['city']);
            $carDealer->setZipcode($carDealerInfos['zipcode']);

            $manager->persist($carDealer);

        }

        $manager->flush();

    }

    private function getCarDealers(): array
    {
        return [
            'paris' => [
                'name' => 'Paris La Défense',
                'address' => '5 Square des Corolles',
                'city' => 'Courbevoie',
                'zipcode' => 92400,
            ],
            'Lyon' => [
                'name' => 'Lyon',
                'address' => '32 Rue de Condé',
                'city' => 'Lyon',
                'zipcode' => 69002,
            ],
        ];
    }
}