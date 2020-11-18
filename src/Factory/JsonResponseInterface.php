<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;

interface JsonResponseInterface
{
    public function getJsonResponse(int $statusCode, string $responseBody): Response;
}
