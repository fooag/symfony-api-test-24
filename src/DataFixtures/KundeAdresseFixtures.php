<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\KundeAdresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class KundeAdresseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $kundeAdresse = new KundeAdresse();
            $kundeAdresse->kunde = $this->getReference("kunde_$i");
            $kundeAdresse->adresse = $this->getReference("adresse_$i");
            $kundeAdresse->geloescht = $i < 3;
            $kundeAdresse->geschaeftlich = $i < 10;
            $kundeAdresse->rechnungsadresse = $i < 20;
            $manager->persist($kundeAdresse);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdresseFixtures::class,
            KundeFixtures::class,
        ];
    }
}
