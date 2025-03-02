<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\GeoDto;
use GeoIp2\ProviderInterface;

class GeoIpDetector implements GeoIpDetectorInterface
{
    public function __construct(
        private readonly ProviderInterface $provider,
        private readonly string $namesLocale,
    ) {
    }

    public function detectGeoByIp(string $ipAddress): GeoDto
    {
        $result = $this->provider->city($ipAddress);

        return new GeoDto(
            $result->continent->name,
            $result->country->name,
            $result->city->name,
            $result->location->latitude,
            $result->location->longitude,
            $result->location->timeZone,
        );        
    }
}