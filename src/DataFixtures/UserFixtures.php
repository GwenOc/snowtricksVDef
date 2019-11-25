<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $userlists = [
            [
                'name'=> 'silverSurfer',
                'email'=> 'silverSurfer@mail.com',                
            ],
            [
                'name'=> 'gamelle',
                'email'=> 'gamelle@mail.com',                
            ],
            [
                'name'=> 'MysticRider',
                'email'=> 'MysticRider@mail.com',                
            ],
            [
                'name'=> 'galacticRider',
                'email'=> 'galacticRider@mail.com',
            ],

        ];

        foreach ($userlists as $key => $userlist) {
            $user = (new User())
                  ->setUserName($userlist['name'])
                  ->setEmail($userlist['email'])
                  ->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'snow'));      
                  
            $this->addReference('name-'.$key, $user);     
            $manager->persist($user);
        }

        $manager->flush();
    }
}
