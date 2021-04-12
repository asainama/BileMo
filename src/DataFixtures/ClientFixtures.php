<?php

namespace App\DataFixtures;

use Faker\Factory as Faker;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface encoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');
        for ($i = 0; $i < 6; $i++) {
            $client = new Client();
            $client
                ->setName($faker->name())
                ->setEmail(($i == 0) ? "test@test.fr" : $faker->email())
                ->setRoles(array('ROLE_USER'))
                ->setPassword($this->encoder->encodePassword($client, 'admin'));
            $manager->persist($client);
            $this->addReference("client_$i", $client);
        }
        $manager->flush();
    }
}
