<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;

class JsonResponse implements JsonResponseInterface
{
    public function getJsonResponse(int $statusCode, string $responseBody): Response
    {
        $response = new Response($responseBody, $statusCode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
