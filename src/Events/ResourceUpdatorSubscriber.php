<?php

declare(strict_types=1);

namespace App\Events;

use App\Entity\User;
use App\Entity\Article;
use App\Authorizations\UserAuthorizationChecker;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Services\ResourceUpdatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResourceUpdatorSubscriber implements EventSubscriberInterface
{    
    private ResourceUpdatorInterface $resourceUpdator;

    public function __construct(ResourceUpdatorInterface $resourceUpdator)
    {
        $this->resourceUpdator = $resourceUpdator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $object = $event->getControllerResult();

        if($object instanceof User || $object instanceof Article) {
            $user = $object instanceof User ? $object : $object->getAuthor();

            $canProcess = $this->resourceUpdator->process($event->getRequest()->getMethod(), $user);
            if($canProcess){
                $user->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}