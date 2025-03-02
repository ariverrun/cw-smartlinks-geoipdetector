<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\GeoDto;
use App\Service\GeoIpDetectorInterface;

class GetGeoByIpUseCase implements GetGeoByIpUseCaseInterface
{
    public function __construct(
        private readonly GeoIpDetectorInterface $geoIpDetector,
    ) {
    }

    public function __invoke(string $ipAddress): GeoDto
    {
        return $this->geoIpDetector->detectGeoByIp($ipAddress);
    }
}