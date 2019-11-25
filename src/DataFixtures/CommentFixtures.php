<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $comments = [
            [
                'trick'=> 0,
                'text'=> 'Mon premier trick, je m\'en souviens comme si c\'était hier.',
                'author'=> 0,
            ],
            [
                'trick'=> 0,
                'text'=> 'C\'est vraiment pour les noob ce trick !',
                'author'=> 1,
            ],
            [
                'trick'=> 1,
                'text'=> 'Super figure ça.',
                'author'=> 2,
            ],
            [
                'trick'=> 1,
                'text'=> '+1 c\est ma préférée.',
                'author'=> 3,
            ],
            [
                'trick'=> 1,
                'text'=> 'C\'est vraiment pour les noob ce trick !',
                'author'=> 1,
            ],
            [
                'trick'=> 2,
                'text'=> 'Il est vachement dur celui la',
                'author'=> 0,
            ],          
            [
                'trick'=> 2,
                'text'=> 'Pas pour les pros',
                'author'=> 1,
            ],
            [
                'trick'=> 2,
                'text'=> 'Faut arreter de se la peter,sérieux...',
                'author'=> 3,
            ],
            [
                'trick'=> 6,
                'text'=> 'Il est vachement dur celui la',
                'author'=> 2,
            ],          
            [
                'trick'=> 6,
                'text'=> 'Pas pour les pros',
                'author'=> 1,
            ],
            [
                'trick'=> 6,
                'text'=> 'Faut arreter de se la peter,sérieux...',
                'author'=> 3,
            ],
            [
                'trick'=> 8,
                'text'=> 'Super figure ça.',
                'author'=> 0,
            ],
            [
                'trick'=> 8,
                'text'=> '+1 c\est ma préférée.',
                'author'=> 2,
            ],
            [
                'trick'=> 8,
                'text'=> 'C\'est vraiment pour les noob ce trick !',
                'author'=> 1,
            ],
            [
                'trick'=> 9,
                'text'=> 'Il est vachement dur celui la',
                'author'=> 2,
            ],          
            [
                'trick'=> 9,
                'text'=> 'Pas pour les pros',
                'author'=> 1,
            ],
            [
                'trick'=> 9,
                'text'=> 'Faut arreter de se la peter,sérieux...',
                'author'=> 3,
            ],

        ];

        foreach ($comments as $comment) {
            $comment = (new Comment())
                ->setText($comment['text'])
                ->setTrick($this->getReference('trick-'.$comment['trick']))
                ->setAuthor($this->getReference('name-'.$comment['author']))
            ;
                 
            $manager->persist($comment);
        }

        $manager->flush();

    }

         public function getDependencies()
    {
        return array(
            TrickFixtures::class,
            UserFixtures::class,
        );
    }
}
