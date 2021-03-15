<?php

namespace Dayuse\IstorijaBundle;

use Dayuse\IstorijaBundle\DependencyInjection\Compiler\CommandHandlerPass;
use Dayuse\IstorijaBundle\DependencyInjection\Compiler\EventHandlerPass;
use Dayuse\IstorijaBundle\DependencyInjection\Compiler\RegisterCommandValidatorPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IstorijaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CommandHandlerPass(), PassConfig::TYPE_AFTER_REMOVING);
        $container->addCompilerPass(new EventHandlerPass(), PassConfig::TYPE_AFTER_REMOVING);
        $container->addCompilerPass(new RegisterCommandValidatorPass());
    }
}
