<?php


namespace App\DataFixtures;


use App\Entity\Rental;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class RentalFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * RentalFixtures constructor.
     */
    public function __construct(UserRepository $userRepository, VehicleRepository $vehicleRepository)
    {
        $this->userRepository = $userRepository;
        $this->vehicleRepository = $vehicleRepository;
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
            VehicleFixtures::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // TODO : Lorsque les fonctions de location seront créer refaire cette fixture en prenant en compte les état des véhicule (loué ou disponible)

        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $vehicle = $this->vehicleRepository->findBy(['state' => true]);


        for ($i = 0; $i <= 10; $i++) {
            $rental = new Rental();
            $rental->setClient($faker->randomElement($users));
            $rental->setVehicle($faker->randomElement($vehicle));
            $rental->setStartRentalDate(new DateTime());
            $rental->setEstimatedReturnDate(new DateTime());
            $rental->setRealReturnDate(new DateTime());
            $rental->setPrice($faker->randomFloat(2, 20, 220));

            $manager->persist($rental);
        }

        $manager->flush();

    }
}