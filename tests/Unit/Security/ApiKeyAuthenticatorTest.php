<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use App\Security\ApiKeyAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class ApiKeyAuthenticatorTest extends TestCase
{
    public function testRefreshUser(): void
    {
        $requestMock = $this->createMock(Request::class);

        $headersMock = $this->createMock(HeaderBag::class);
        $headersMock->expects($this->once())
                    ->method('get')
                    ->willReturn(null);

        $requestMock->headers = $headersMock;

        $apiKeyAuthenticator = new ApiKeyAuthenticator();

        $this->expectException(AuthenticationException::class);

        $apiKeyAuthenticator->authenticate($requestMock);
    }
}