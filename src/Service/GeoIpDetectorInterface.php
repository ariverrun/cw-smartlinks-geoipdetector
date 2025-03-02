<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\GeoDto;

interface GeoIpDetectorInterface
{
    public function detectGeoByIp(string $ipAddress): GeoDto;
}