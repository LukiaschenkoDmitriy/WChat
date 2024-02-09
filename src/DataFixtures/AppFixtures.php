<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Cup;
use App\Entity\Member;
use App\Entity\Pen;
use App\Entity\Pencil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
    }
}
