<?php

namespace Dayuse\IstorijaBundle\Command;

use Dayuse\IstorijaBundle\ServiceHandlerRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugHandlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('istorija:debug:handler')
            ->setDescription('Debug command and event handlers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        
        /** @var ServiceHandlerRegistry $serviceHandlerRegistry */
        $serviceHandlerRegistry = $this->getContainer()->get('istorija.service_handler_registry');
        
        $io->title('Configured service event handlers');
        $io->listing($serviceHandlerRegistry->getServiceEventHandlers());

        $io->title('Configured service command handlers');
        $io->listing(array_keys($serviceHandlerRegistry->getCommandHandlers()));

        return 0;
    }
}