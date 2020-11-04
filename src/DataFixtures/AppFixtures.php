<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($u = 0; $u < 10 ; $u++) {
            $user = new User();
            $passHash = $this->encoder->encodePassword($user, "123456");

            $user->setEmail($faker->email)
                ->setPassword($passHash);
            
            $manager->persist($user);

            for($a = 0; $a < random_int(3, 6); $a++) {
                $article = (new Article())->setAuthor($user)
                        ->setContent($faker->text(300))
                        ->setName($faker->text(50));

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
