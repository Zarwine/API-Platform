<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = ['ACCEPT' =>'application/json', 'CONTENT_TYPE' => 'application/json'];
    public function getResponseFromRequest(string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();

        $client->request(
            $method,
            $uri . '.json',
            [],
            [],
            $this->serverInformations,
            $payload
        );

        return $client->getResponse();
        
    }
}