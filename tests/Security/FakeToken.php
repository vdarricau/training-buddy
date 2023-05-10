<?php

declare(strict_types=1);

namespace App\Tests\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * @method string getUserIdentifier()
 */
class FakeToken extends AbstractToken
{
    public function getCredentials(): array
    {
        return [];
    }
}
