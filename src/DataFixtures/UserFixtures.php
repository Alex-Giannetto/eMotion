<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;


    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
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
        $owner = new User();
        $owner->setEmail('employee@mail.com');
        $owner->setRoles(['ROLE_EMPLOYEE']);
        $owner->setFirstname('Employee');
        $owner->setLastname('Employee');
        $owner->setAddress($faker->address);
        $owner->setCity($faker->city);
        $owner->setZipCode($faker->postcode);
        $owner->setBirthDate($faker->dateTime);
        $owner->setDriverLicense(strtoupper(substr($faker->md5, 0, 25)));
        $owner->setPhoneNumber($faker->phoneNumber);

        $password = $this->userPasswordEncoder->encodePassword($owner, 'password');
        $owner->setPassword($password);

        $manager->persist($owner);


        // Client
        for ($i = 0; $i < 10; $i++) {
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

}