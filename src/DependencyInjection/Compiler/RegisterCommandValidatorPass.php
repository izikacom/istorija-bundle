<?php

namespace Dayuse\IstorijaBundle\DependencyInjection\Compiler;

use Dayuse\Istorija\CommandBus\CommandBusValidator;
use Dayuse\Istorija\CommandBus\CommandValidator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterCommandValidatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(CommandBusValidator::class)) {
            return;
        }

        $definition = $container->getDefinition(CommandBusValidator::class);

        $validators = $definition->getArgument(1);
        foreach ($container->findTaggedServiceIds('istorija.command_validator') as $id => $tags) {
            $validators = array_merge($validators, $this->getCommandValidators($container, $id, $tags));
        }

        $definition->replaceArgument(1, $validators);
    }

    private function registerCommandValidators(ContainerBuilder $container, string $id, array $tags): array
    {
        $validators = [];
        foreach ($tags as $tag) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();
            $rc = new \ReflectionClass($class);
            if (!$rc->implementsInterface(CommandValidator::class)) {
                throw new \RuntimeException(sprintf('Class "%s" of service "%s" must implement interface "%s" in order to be considered a valid command validator.', $class, $id, CommandValidator::class));
            }

            $validators[] = new Reference($id);
        }

        return $validators;
    }
}
