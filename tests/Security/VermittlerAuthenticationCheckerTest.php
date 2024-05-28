<?php

namespace App\Tests\Security;

use App\Entity\VermittlerUser;
use App\Security\VermittlerAuthenticationChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class VermittlerAuthenticationCheckerTest extends TestCase
{
    public function testCheckPreAuthThrowsNoExceptionForActiveVermittler(): void
    {
        $this->expectNotToPerformAssertions();

        $vermittlerUser = new VermittlerUser();
        $vermittlerUser->aktiv = 1;

        $checker = new VermittlerAuthenticationChecker();
        $checker->checkPreAuth($vermittlerUser);
    }

    public function testCheckPreAuthThrowsExceptionForInactiveVermittler(): void
    {
        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Der Account existiert nicht mehr.');

        $vermittlerUser = new VermittlerUser();
        $vermittlerUser->aktiv = 0;

        $checker = new VermittlerAuthenticationChecker();
        $checker->checkPreAuth($vermittlerUser);
    }
}
