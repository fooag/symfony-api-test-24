<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\VermittlerUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class VermittlerAuthenticationChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof VermittlerUser) {
            return;
        }
        if ($user->aktiv !== 1) {
            throw new CustomUserMessageAccountStatusException('Der Account existiert nicht mehr.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // no checks - intentionally left empty
    }
}