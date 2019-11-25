<?php

namespace App\DataFixtures;

use App\DataFixtures\CategoryFixtures;
use App\Entity\Trick;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tricks = [
            [
                'name'=> 'mute',
                'description'=> 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                'category'=> 0, 
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']              
            ],
            [
                'name'=> 'stalefish',
                'description'=> 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière',
                'category'=> 0,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> '360',
                'description'=> 'trois six pour un tour complet',
                'category'=> 1,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'front flips',
                'description'=> 'Rotation verticale en avant',
                'category'=> 2,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'corkscrew',
                'description'=> 'Rotation désaxée avec le buste en avant',
                'category'=> 3,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'nose slide',
                'description'=> 'Glisser sur une barre de slide,l\'avant de la planche sur la barre',
                'category'=> 4,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'truck driver',
                'description'=> 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture).',
                'category'=> 0,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'Tailgrab',
                'description'=> 'grabez l\'arrière de sa planche, c\'est un grab difficile mais
                   très esthétique.',
                'category'=> 0,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']  
            ],
            [
                'name'=> 'Melancholie',
                'description'=> 'en rotation backside avec la jambe avant tendue',
                'category'=> 1,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']
            ],
            [
                'name'=> 'Le switch',
                'description'=> 'On appelle switch le fait de rider à l\'envers (goofy en régular et vice et versa). L\'ensemble des tricks susnommés peuvent s\'effectuer en switch cependant l\'héritage du skate nous a imposé des termes spécifique pour certaines figures, ainsi une rotation front effectuée en switch sera un cab (de CABALLERO inventeur du caballerial figure de skate). ',
                'category'=> 6,
                'videolinks'=>['<iframe width="560" height="315" src="https://www.youtube.com/embed/1TJ08caetkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>']
            ],
        ];

        foreach ($tricks as $key => $trick) {
            $newTrick = (new Trick())
                ->setName($trick['name'])
                ->setDescription($trick['description'])
                ->setVideolinks($trick['videolinks'])
                ->setCategory($this->getReference('category-'.$trick['category']))
                ->setAuthor($this->getReference('name-1'))
            ;

            $this->addReference('trick-'.$key, $newTrick);     
            $manager->persist($newTrick);
        }
        

        $manager->flush();
    }

     public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
