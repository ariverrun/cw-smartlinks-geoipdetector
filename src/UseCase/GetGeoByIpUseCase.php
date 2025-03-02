<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\GeoDto;
use App\Exception\IpIsNotFoundException;
use App\Service\GeoIpDetectorInterface;
use Throwable;

class GetGeoByIpUseCase implements GetGeoByIpUseCaseInterface
{
    public function __construct(
        private readonly GeoIpDetectorInterface $geoIpDetector,
    ) {
    }

    public function __invoke(string $ipAddress): GeoDto
    {
        try {
            return $this->geoIpDetector->detectGeoByIp($ipAddress);
        } catch (Throwable $e) {
            throw new IpIsNotFoundException(previous: $e);
        }
    }
}
