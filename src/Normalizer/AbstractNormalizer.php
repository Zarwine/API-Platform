<?php

declare(strict_types=1);

namespace App\Normalizer;

abstract class AbstractNormalizer implements NormalizerInterface
{
    private array $exceptionTypes;

    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }

    public function supports(\Exception $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}