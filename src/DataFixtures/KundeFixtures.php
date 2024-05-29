<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Kunde;
use App\Enumeration\Geschlecht;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class KundeFixtures  extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $kunde = new Kunde();
            $kunde->email = "kunde_$i@email.com";
            $kunde->vorname = "Vorname #$i";
            $kunde->nachname = "Nachname #$i";
            $kunde->geburtsdatum = (new DateTime('2000-01-01'))->modify('+1 year');
            $kunde->geschlecht = Geschlecht::DIVERS->value;
            $kunde->firma = "Vorname #$i";
            $kunde->geloescht = $i < 3 ? 1 : 0;
            $kunde->vermittler = match ($i % 3) {
                0 => $this->getReference(VermittlerFixtures::KLAUS_WARNER),
                1 => $this->getReference(VermittlerFixtures::SVENJA_SCHUSTER),
                2 => $this->getReference(VermittlerFixtures::VINCENT_VINCENT),
            };
            $manager->persist($kunde);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VermittlerFixtures::class,
        ];
    }
}
