<?php

declare(strict_types=1);

namespace Tests\Unit\UseCase;

use App\Exception\IpIsNotFoundException;
use App\Service\GeoIpDetectorInterface;
use App\UseCase\GetGeoByIpUseCase;
use PHPUnit\Framework\TestCase;
use Exception;

final class GetGeoByIpUseCaseTest extends TestCase
{
    public function testThrowsIpIsNotFoundExceptionOnServiceError(): void
    {
        $geoIpDetectorMock = $this->createMock(GeoIpDetectorInterface::class);

        $geoIpDetectorMock->expects($this->once())
                            ->method('detectGeoByIp')
                            ->willThrowException($this->createMock(Exception::class));
        
        $getGeoByIpUseCase = new GetGeoByIpUseCase($geoIpDetectorMock);

        $this->expectException(IpIsNotFoundException::class);

        ($getGeoByIpUseCase)('');
    }
}