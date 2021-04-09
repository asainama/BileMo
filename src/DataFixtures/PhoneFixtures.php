<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $brands = array(
            "Samsung" => array(
                "S",
                "A",
                "M",
                "Z",
                "Note"
            ),
            "Asus" => array(
                "ZenFone"
            ),
            "Xiaomi" => array(
                "Mi",
                "Redmi",
                "Poco"
            ),
            "Apple" => array(
                "Iphone"
            )
        );
        for ($i = 0; $i < 8; $i++) {
            foreach ($brands as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $mobile = new Phone();
                        $name = $v . random_int(3, 10);
                        $mobile
                            ->setName($name)
                            ->setBrand($key)
                            ->setDescription("This phone is $name of the $key")
                            ->setPrice(mt_rand(200, 1400))
                            ->setMemory(random_int(32, 512));
                        $manager->persist($mobile);
                    }
                }
            }
        }
        $manager->flush();
    }
}
