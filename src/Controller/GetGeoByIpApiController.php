<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\GetGeoByIpUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class GetGeoByIpApiController extends AbstractController
{
    #[Route('/api/v1/geo', methods: ['GET'], name: 'api_geo_get')]
    public function __invoke(
        #[MapQueryParameter]
        string $ip,
        GetGeoByIpUseCaseInterface $useCase,
    ): JsonResponse {
        return $this->json([
            'data' => ($useCase)($ip),
        ]);
    }
}
