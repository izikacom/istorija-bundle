<?php

namespace Dayuse\IstorijaBundle\DependencyInjection\Compiler;

use Dayuse\Istorija\CommandBus\CommandBus;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(CommandBus::class) && !$container->hasAlias(CommandBus::class)) {
            return;
        }

        $commandBus             = $container->findDefinition(CommandBus::class);
        $serviceHandlerRegistry = $container->findDefinition('istorija.service_handler_registry');
        $taggedCommandHandlers  = $container->findTaggedServiceIds('command_handler');

        foreach ($taggedCommandHandlers as $id => $tags) {
            $commandHandler           = $container->findDefinition($id);
            $commandHandlerReflection = new \ReflectionClass($commandHandler->getClass());
            $commandHandlerMethods    = $commandHandlerReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $commandHandlerMethods    = array_filter($commandHandlerMethods, function (\ReflectionMethod $reflectionMethod) {
                return 0 === strpos($reflectionMethod->getName(), "handle");
            });

            $handlers = [];

            /** @var \ReflectionMethod $commandHandlerMethod */
            foreach ($commandHandlerMethods as $commandHandlerMethod) {
                /** @var \ReflectionParameter $parameter */
                $parameter = $commandHandlerMethod->getParameters()[0];

                $handler = [$commandHandler, $commandHandlerMethod->getName()];

                $commandBus->addMethodCall(
                    'register',
                    [
                        $parameter->getClass()->getName(),
                        $handler,
                    ]
                );

                $handlers[] = $handler;
            }

            $serviceHandlerRegistry->addMethodCall(
                'registerCommandHandlers',
                [
                    $id,
                    $handlers,
                ]
            );
        }
    }
}
