<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Vermittler;
use App\Service\CroppedUuid4Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VermittlerFixtures extends Fixture
{
    public const KLAUS_WARNER = 'vermittler_klaus_warner';
    public const SVENJA_SCHUSTER = 'vermittler_svenja_schuster';
    public const VINCENT_VINCENT = 'vermittler_vincent_vincent';

    public function load(ObjectManager $manager)
    {
        $this->create(
            manager: $manager,
            vorname: 'Klaus',
            nachname: 'Warner',
            geloescht: false,
            firma: 'Company Ltd.',
            reference: self::KLAUS_WARNER,
        );
        $this->create(
            manager: $manager,
            vorname: 'Svenja',
            nachname: 'Schuster',
            geloescht: false,
            firma: 'BazBar',
            reference: self::SVENJA_SCHUSTER,
        );
        $this->create(
            manager: $manager,
            vorname: 'Vincent',
            nachname: 'Vincent',
            geloescht: true,
            firma: 'Firma GmbH',
            reference: self::VINCENT_VINCENT,
        );

        $manager->flush();
    }

    private function create(
        ObjectManager $manager,
        string $vorname,
        string $nachname,
        bool $geloescht,
        ?string $firma,
        string $reference
    ): void {
        $vermittler = new Vermittler();
        $vermittler->nummer = (new CroppedUuid4Generator())->generateUppercase(8);
        $vermittler->vorname = $vorname;
        $vermittler->nachname = $nachname;
        $vermittler->geloescht = $geloescht;
        $vermittler->firma = $firma;

        $manager->persist($vermittler);
        $this->addReference($reference, $vermittler);
    }
}
