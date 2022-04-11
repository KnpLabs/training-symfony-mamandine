<?php

namespace App\DataFixtures;

use App\Entity\Cake;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CakeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $categories = $this->categories->findAll();

        for ($i = 0; $i < 1000; $i++) {
            $cake = new Cake();
            $cake->setName($faker->word());
            $cake->setDescription($faker->paragraph(1));
            $cake->setPrice($faker->randomFloat(2, 10, 100));
            $cake->setImage($faker->imageUrl(640, 480, 'cake', true));
            $cake->addCategory($categories[$faker->numberBetween(0, 9)]);

            $manager->persist($cake);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
