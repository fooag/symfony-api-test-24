<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Enumeration\Bundesland;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdresseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $adresse = new Adresse();
            $adresse->bundesland = Bundesland::BERLIN->value;
            $adresse->ort = 'Berlin';
            $adresse->plz = '14109';
            $adresse->strasse = "Kronprinzessinnenweg $i";

            $this->addReference("adresse_$i", $adresse);
            $manager->persist($adresse);
        }
        $manager->flush();
    }
}
