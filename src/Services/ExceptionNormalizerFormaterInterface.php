<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

interface ExceptionNormalizerFormaterInterface
{
    public function format(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): array;
}
