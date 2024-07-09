<?php

namespace Dayuse\IstorijaBundle\Command;

use Dayuse\IstorijaBundle\ServiceHandlerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugHandlerCommand extends Command
{
    protected static $defaultName = 'istorija:debug:handler';

    public function __construct(
        private readonly ServiceHandlerRegistry $serviceHandlerRegistry,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Debug command and event handlers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Configured service event handlers');
        $io->listing($this->serviceHandlerRegistry->getServiceEventHandlers());

        $io->title('Configured service command handlers');
        $io->listing(array_keys($this->serviceHandlerRegistry->getCommandHandlers()));

        return 0;
    }
}
