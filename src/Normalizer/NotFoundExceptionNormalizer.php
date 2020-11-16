<?php

declare(strict_types=1);

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;

class NotFoundExceptionNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception): array
    {
        return $this->exceptionNormalizerFormater->format($exception->getMessage(), Response::HTTP_NOT_FOUND);
    }
}
