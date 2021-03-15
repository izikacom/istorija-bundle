<?php

namespace Dayuse\IstorijaBundle\DependencyInjection;

use Dayuse\IstorijaBundle\Command\DebugHandlerCommand;
use Dayuse\IstorijaBundle\ServiceHandlerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class IstorijaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->register('istorija.service_handler_registry', ServiceHandlerRegistry::class)
            ->setPublic(true);

        $container->register('istorija.console.debug_handler', DebugHandlerCommand::class)
            ->setPublic(false)
            ->addTag('console.command', [
                'command' => 'istorija:debug:handler',
            ]);
    }
}
