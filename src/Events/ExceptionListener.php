<?php

declare(strict_types=1);

namespace App\Events;

use App\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionListener implements EventSubscriberInterface
{
    private static array $normalizers;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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
            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body'] = [
                'code'    => $result['code'],
                'message' => $exception->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $response = new Response($body, $result['code']);
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }
    
    public function addNormalizer(NormalizerInterface $normalizer)
    {
        self::$normalizers[] = $normalizer;
    }

}