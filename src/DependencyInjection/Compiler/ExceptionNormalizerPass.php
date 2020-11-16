<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExceptionNormalizerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $exceptionListenerDefinition = $container->findDefinition('miniblog.events.exception_subscriber');

        $normalizers = $container->findTaggedServiceIds('miniblog.normalizer');

        foreach($normalizers as $normalizer => $tags) {
            $exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($normalizer)]);
        }
    }
}