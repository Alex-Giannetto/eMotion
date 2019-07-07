<?php


namespace App\DataFixtures;


use App\Entity\Vehicle;
use App\Entity\VehicleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class VehicleFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $vehicleTypes = $this->generateVehicleType($manager);
        $this->generateCar($manager, $faker, $vehicleTypes[0]);


    }


    private function generateVehicleType(ObjectManager $manager): array
    {
        $car = new VehicleType();
        $car->setLabel('Voiture');

        $scooter = new VehicleType();
        $scooter->setLabel('Scooter');

        $manager->persist($car);
        $manager->persist($scooter);

        $manager->flush();

        return [$car, $scooter];
    }

    private function generateCar(ObjectManager $manager, Generator $faker, VehicleType $vehicleType)
    {
        $carBrandsModels = $this->getCarModels();

        for ($i=0;$i<50;$i++){
            $brand = $faker->randomElement($carBrandsModels);
            $brandName = array_search($brand, $carBrandsModels);
            $modelName = $faker->randomElement($brand);

            $car = new Vehicle();
            $car->setBrand($brandName);
            $car->setModel($modelName);
            $car->setVehicleType($vehicleType);
            $car->setColor($faker->safeColorName);
            $car->setAutonomy(rand(200, 600));
            $car->setKilometers(rand(0, 50));
            $car->setDailyDistance(rand(50, 200));
            $car->setDailyPrice($faker->randomFloat(2, 35, 100));
            $car->setMinDailyPrice(rand(10, 25));
            $car->setPurchasingDate($faker->dateTime('now'));
            $car->setPurchasingPrice(rand(35000, 120000));
            $car->setMatriculation(strtoupper(substr($faker->sha1, 0, 10)));
            $car->setSerialNumber(strtoupper(substr($faker->sha1, 0, 10)));

            $manager->persist($car);
        }

        $manager->flush();
    }


    private function getCarModels(): array
    {
        return [
            'Tesla' => [
                'Model S',
                'Model 3',
                'Model X',
                'Modem Y',
            ],
            'Renault' => [
                'Zoe',
                'Twizi'
            ],
            'Nissan' => [
                'Leaf'
            ],
            'BMW' => [
                'i3',
                'i8'
            ],
        ];

    }
}