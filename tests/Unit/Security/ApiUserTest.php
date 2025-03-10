<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use App\Security\ApiUser;
use PHPUnit\Framework\TestCase;

final class ApiUserTest extends TestCase
{
    public function testRefreshUser(): void
    {
        $apiKey = '123';

        $user = new ApiUser($apiKey);

        $this->assertEquals($apiKey, $user->getUserIdentifier());
    }
}