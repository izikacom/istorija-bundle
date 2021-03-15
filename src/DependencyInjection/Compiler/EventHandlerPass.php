<?php

namespace Dayuse\IstorijaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EventHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $serviceHandlerRegistry = $container->findDefinition('istorija.service_handler_registry');
        $taggedEventHandlers = $container->findTaggedServiceIds('event_handler');

        foreach ($taggedEventHandlers as $id => $tags) {
            $serviceHandlerRegistry->addMethodCall(
                'registerServiceEventHandlers',
                [
                    $id,
                ]
            );
        }
    }
}
