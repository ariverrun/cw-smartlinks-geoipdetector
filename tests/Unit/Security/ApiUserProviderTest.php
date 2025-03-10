<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use App\Security\ApiUser;
use App\Security\ApiUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

final class ApiUserProviderTest extends TestCase
{
    public function testRefreshUser(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $apiUserProvider = new ApiUserProvider([]);

        $refreshedUser = $apiUserProvider->refreshUser($userMock);

        $this->assertEqualsCanonicalizing($userMock, $refreshedUser);
    }

    public function testSupportsRightClass(): void
    {
        $apiUserProvider = new ApiUserProvider([]);

        $this->assertTrue($apiUserProvider->supportsClass(ApiUser::class));
    }
}