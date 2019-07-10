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

        $paris = new CarDealer();
        $paris->setName('Paris La Défense');
        $paris->setAddress('5 Square des Corolles');
        $paris->setCity('Courbevoie');
        $paris->setZipcode(92400);


        $manager->persist($paris);
        $manager->flush();

    }
}