<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur'
    ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName){
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
        }
        $manager->flush();
    }
}
