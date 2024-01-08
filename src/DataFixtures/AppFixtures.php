<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 20; $i++) {
            $tode = new Todo();
            $rand = random_int(0,100);
            $tode->setName($rand);
            $tode->setDescription('Manger');
            $manager->persist($tode);
        }

        $manager->flush();
    }
}
