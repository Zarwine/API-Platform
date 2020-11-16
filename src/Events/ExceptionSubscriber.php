<?php

declare(strict_types=1);

namespace App\Events;

use App\Factory\JsonResponseInterface;
use App\Normalizer\NormalizerInterface;
use App\Services\ExceptionNormalizerFormaterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private static array $normalizers;
    private SerializerInterface $serializer;
    private ExceptionNormalizerFormaterInterface $exceptionNormalizerFormater;
    private JsonResponseInterface $jsonResponse;

    public function __construct(
        SerializerInterface $serializer,
        ExceptionNormalizerFormaterInterface $exceptionNormalizerFormater,
        JsonResponseInterface $jsonResponse
        )    {
        $this->serializer = $serializer;
        $this->exceptionNormalizerFormater = $exceptionNormalizerFormater;
        $this->jsonResponse = $jsonResponse;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 0]]
        ];
    }

    public function processException(ExceptionEvent $event)
    {
        $result = null;
        /** @var \Exception $exception */
        $exception = $event->getThrowable();

        /** @var NormalizerInterface $normalizer */
        foreach (self::$normalizers as $key => $normalizer) {
            if($normalizer->supports($exception)) {
                $result = $normalizer->normalize($exception);
                break;
            }
        }

        if (null === $result) {
            $result = $this->exceptionNormalizerFormater->format($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $event->setResponse($this->jsonResponse->getJsonResponse(
            $result['code'],
            $this->serializer->serialize($result, 'json')
        ));
    }
    
    public function addNormalizer(NormalizerInterface $normalizer)
    {
        self::$normalizers[] = $normalizer;
    }

}