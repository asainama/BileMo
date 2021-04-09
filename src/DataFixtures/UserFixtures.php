<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            /** @var Client $client */
            $client = $this->getReference('client_'. random_int(0, 5));
            $user
                ->setEmail($faker->email())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhoneNumber($faker->e164PhoneNumber())
                ->setClient($client)
                ->setZipcode($faker->postcode())
                ->setAddress($faker->address())
                ->setCity($faker->city());
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}
