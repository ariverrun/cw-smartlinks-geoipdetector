<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\GeoDto;

interface GetGeoByIpUseCaseInterface
{
    public function __invoke(string $ipAddress): GeoDto;
}
