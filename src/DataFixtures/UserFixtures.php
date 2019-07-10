<?php


namespace App\DataFixtures;


use App\Entity\User;
use App\Repository\CarDealerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var CarDealerRepository
     */
    private $carDealerRepository;


    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, CarDealerRepository $carDealerRepository)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->carDealerRepository = $carDealerRepository;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');


        // Admin
        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setAddress($faker->address);
        $admin->setCity($faker->city);
        $admin->setZipCode($faker->postcode);
        $admin->setBirthDate($faker->dateTime);
        $admin->setDriverLicense(strtoupper(substr($faker->md5, 0, 25)));
        $admin->setPhoneNumber($faker->phoneNumber);

        $password = $this->userPasswordEncoder->encodePassword($admin, 'password');
        $admin->setPassword($password);

        $manager->persist($admin);


        //Employee
        $carDealers = $this->carDealerRepository->findAll();

        for ($i = 1; $i < 6; $i++) {
            $employee = new User();
            $employee->setEmail('employee' . $i . '@mail.com');
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setFirstname('Employee n°' . $i);
            $employee->setLastname('Employee');
            $employee->setAddress($faker->address);
            $employee->setCity($faker->city);
            $employee->setZipCode($faker->postcode);
            $employee->setBirthDate($faker->dateTime);
            $employee->setDriverLicense(strtoupper(substr($faker->md5, 0, 25)));
            $employee->setPhoneNumber($faker->phoneNumber);
            $employee->setCarDealer($faker->randomElement($carDealers));
            $password = $this->userPasswordEncoder->encodePassword($employee, 'password');
            $employee->setPassword($password);
            $manager->persist($employee);
        }


        // Client
        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setAddress($faker->address);
            $user->setCity($faker->city);
            $user->setZipCode($faker->postcode);
            $user->setBirthDate($faker->dateTime);
            $user->setDriverLicense(strtoupper(substr($faker->md5, 0, 25)));
            $user->setPhoneNumber($faker->phoneNumber);

            $password = $this->userPasswordEncoder->encodePassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
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
            CarDealerFixtures::class
        ];
    }
}