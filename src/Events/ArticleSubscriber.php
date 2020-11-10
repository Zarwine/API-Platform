<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User;
use App\Authorizations\ArticleAuthorizationChecker;
use App\Authorizations\UserAuthorizationChecker;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleSubscriber implements EventSubscriberInterface
{    
    private array $methodNotAllowed = [
        Request::METHOD_POST,
        Request::METHOD_GET
    ];
    private ArticleAuthorizationChecker $articleAuthorizationChecker;

    public function __construct(ArticleAuthorizationChecker $articleAuthorizationChecker)
    {
        $this->articleAuthorizationChecker = $articleAuthorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function encodePassword(ViewEvent $event): void
    {
        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($article instanceof Article && !in_array($method, $this->methodNotAllowed, true)) {
            $this->articleAuthorizationChecker->check($article, $method);
            $article->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}