<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class ExceptionNormalizerFormater implements ExceptionNormalizerFormaterInterface
{
    public function format(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): array
    {
        return [
            'code' => $statusCode,
            'message' => $message,
        ];
    }
}
