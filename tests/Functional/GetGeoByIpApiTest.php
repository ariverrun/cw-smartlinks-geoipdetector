<?php

declare(strict_types=1);

namespace Tests\Functional;

use GeoIp2\ProviderInterface;
use GeoIp2\Model\City;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ReflectionClass;
use Exception;
use stdClass;

final class GetGeoByIpApiTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testSuccessfulRequest(): void
    {
        $container = $this->client->getContainer();

        $geoIp2Provider = $this->createMock(ProviderInterface::class);

        $continentName = 'Europe';
        $countryName = 'France';
        $cityName = 'Paris';
        $latitude = 48.864716;
        $longitude = 2.349014;
        $timeZone = 'Europe/Paris';
        
        $resultMock = $this->createMock(City::class);
        $resultMock->method('__get')->willReturnCallback(function ($property) use ($continentName, $countryName, $cityName, $latitude, $longitude, $timeZone) {
            switch ($property) {
                case 'continent':
                    $continentMock = new stdClass();
                    $continentMock->name = $continentName;
                    return $continentMock;
                case 'country':
                    $countryMock = new stdClass();
                    $countryMock->name = $countryName;
                    return $countryMock;
                case 'city':
                    $cityMock = new stdClass();
                    $cityMock->name = $cityName;
                    return $cityMock;
                case 'location':
                    $locationMock = new stdClass();
                    $locationMock->latitude = $latitude;
                    $locationMock->longitude = $longitude;
                    $locationMock->timeZone = $timeZone;
                    return $locationMock;
                default:
                    return null;
            }
        }); 
        

        $geoIp2Provider->expects($this->once())
                        ->method('city')
                        ->willReturn($resultMock);

        $container->set(ProviderInterface::class, $geoIp2Provider);

        $ipAddress = '28.247.118.215';

        $apiKey = json_decode($_ENV['CLIENT_API_KEYS'])[0];

        $this->client->jsonRequest('GET', '/api/v1/geo?ip=' . $ipAddress, [], [
            'HTTP_X-API-TOKEN' => $apiKey,
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);        

        $geoData = $responseData['data'];

        $this->assertArrayHasKey('continent', $geoData);
        $this->assertIsString($geoData['continent']);      
        $this->assertEquals($geoData['continent'], $continentName);

        $this->assertArrayHasKey('country', $geoData);
        $this->assertIsString($geoData['country']);      
        $this->assertEquals($geoData['country'], $countryName);
        
        $this->assertArrayHasKey('city', $geoData);
        $this->assertIsString($geoData['city']);      
        $this->assertEquals($geoData['city'], $cityName);
        
        $this->assertArrayHasKey('latitude', $geoData);
        $this->assertIsFloat($geoData['latitude']);      
        $this->assertEquals($geoData['latitude'], $latitude);
        
        $this->assertArrayHasKey('longitude', $geoData);
        $this->assertIsFloat($geoData['longitude']);      
        $this->assertEquals($geoData['longitude'], $longitude);
        
        $this->assertArrayHasKey('timeZone', $geoData);
        $this->assertIsString($geoData['timeZone']);      
        $this->assertEquals($geoData['timeZone'], $timeZone);        
    }

    public function testFailedRequestWithUnknownIp(): void
    {
        $container = $this->client->getContainer();

        $geoIp2Provider = $this->createMock(ProviderInterface::class);

        $geoIp2Provider->expects($this->once())
                        ->method('city')
                        ->willThrowException($this->createMock(Exception::class));

        $container->set(ProviderInterface::class, $geoIp2Provider);

        $ipAddress = '28.247.118.215';

        $apiKey = json_decode($_ENV['CLIENT_API_KEYS'])[0];

        $this->client->jsonRequest('GET', '/api/v1/geo?ip=' . $ipAddress, [], [
            'HTTP_X-API-TOKEN' => $apiKey,
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testFailedRequestWithWrongApiKey(): void
    {
        $ipAddress = '28.247.118.215';

        $apiKey = 'abc';

        $this->client->jsonRequest('GET', '/api/v1/geo?ip=' . $ipAddress, [], [
            'HTTP_X-API-TOKEN' => $apiKey,
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testFailedRequestWithoutApiKey(): void
    {
        $ipAddress = '28.247.118.215';

        $this->client->jsonRequest('GET', '/api/v1/geo?ip=' . $ipAddress);

        $this->assertResponseStatusCodeSame(401);
    }

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }
}