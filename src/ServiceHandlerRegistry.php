<?php
namespace Dayuse\IstorijaBundle;


use Dayuse\Istorija\Utils\Ensure;

class ServiceHandlerRegistry
{
    /**
     * @var string[]
     */
    private $eventHandlers;

    /**
     * @var string[]
     */
    private $commandHandlers;

    public function __construct()
    {
        $this->eventHandlers   = [];
        $this->commandHandlers = [];
    }

    public function registerServiceEventHandlers(string $serviceId): void
    {
        Ensure::notInArray($serviceId, $this->eventHandlers, 'Event handlers already registered');

        $this->eventHandlers[] = $serviceId;
    }

    public function registerCommandHandlers(string $serviceId, array $handlerMethods): void
    {
        Ensure::allIsCallable($handlerMethods);
        Ensure::keyNotExists($this->commandHandlers, $serviceId, 'Command handlers already registered');

        $this->commandHandlers[$serviceId] = $handlerMethods;
    }

    public function getServiceEventHandlers(): array
    {
        return $this->eventHandlers;
    }

    public function getCommandHandlers(): array
    {
        return $this->commandHandlers;
    }
}