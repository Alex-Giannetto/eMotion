<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->resetAutoIncrement('user', 'vehicle' ,'vehicle_type');
    }


    /**
     * Reset the auto increment method in the database for table name passed in parameters
     * @param string ...$tables
     */
    private function resetAutoIncrement(string ...$tables): void
    {
        $databaseConnect = $this->entityManager->getConnection();
        foreach ($tables as $table) {
            $sql = "ALTER TABLE $table AUTO_INCREMENT = 1";
            $stmt = $databaseConnect->prepare($sql);
            $stmt->execute();
        }
    }
}
