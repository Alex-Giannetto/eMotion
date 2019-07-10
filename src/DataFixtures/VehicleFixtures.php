<?php


namespace App\DataFixtures;


use App\Entity\Vehicle;
use App\Entity\VehicleType;
use App\Repository\CarDealerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var CarDealerRepository
     */
    private $carDealerRepository;

    /**
     * VehicleFixtures constructor.
     */
    public function __construct(CarDealerRepository $carDealerRepository)
    {
        $this->carDealerRepository = $carDealerRepository;
    }


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $carDealers = $this->carDealerRepository->findAll();

        $vehicleTypes = $this->generateVehicleType($manager);
        $this->generateVehicule($manager, $faker, $vehicleTypes[0], $this->getCarModels(), $carDealers, 50);
        $this->generateVehicule($manager, $faker, $vehicleTypes[1], $this->getScooterModels(), $carDealers, 15);
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

    private function generateVehicule(ObjectManager $manager, Generator $faker, VehicleType $vehicleType, array $models, array $carDealers, int $quantity)
    {

        for ($i = 0; $i <= $quantity; $i++) {
            $brand = $faker->randomElement($models);
            $brandName = array_search($brand, $models);
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
            $car->setCarDealer($faker->randomElement($carDealers));

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

    private function getScooterModels(): array
    {
        return [
            'Unu' => [
                'Unu'
            ],
            'Gogoro' => [
                'Gogoro'
            ],
            'Niu' => [
                'Series M',
                'Series J'
            ],
            'BMW' => [
                'C Evolution'
            ]
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CarDealerFixtures::class,
        ];
    }
}