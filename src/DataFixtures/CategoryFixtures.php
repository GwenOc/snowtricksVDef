<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoryNames = [
            'Les grabs',
            'Les rotations',
            'les flips',
            'Les rotations désaxées',
            'Les slides',
            'Les one Foot tricks',
            'Old school'    
        ];

        foreach ($categoryNames as $key => $categoryName) {
            $category = new Category();
            $category->setCategoryName($categoryName);
            $this->addReference('category-'.$key, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
