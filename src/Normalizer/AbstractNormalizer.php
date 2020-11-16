<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Services\ExceptionNormalizerFormaterInterface;

abstract class AbstractNormalizer implements NormalizerInterface
{
    private array $exceptionTypes;
    protected ExceptionNormalizerFormaterInterface $exceptionNormalizerFormater;

    public function __construct(array $exceptionTypes, ExceptionNormalizerFormaterInterface $exceptionNormalizerFormater)
    {
        $this->exceptionTypes = $exceptionTypes;
        $this->exceptionNormalizerFormater = $exceptionNormalizerFormater;
    }

    public function supports(\Exception $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes, true);
    }
}