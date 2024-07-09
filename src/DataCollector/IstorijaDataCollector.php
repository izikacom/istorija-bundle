<?php

namespace Dayuse\IstorijaBundle\DataCollector;


use Dayuse\Istorija\CommandBus\CommandBus;
use Dayuse\Istorija\CommandBus\TraceableCommandBus;
use Dayuse\Istorija\EventBus\EventBus;
use Dayuse\Istorija\EventBus\TraceableEventBus;
use Dayuse\Istorija\Messaging\Message;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseDataCollector;
use Verraes\ClassFunctions\ClassFunctions;
use function count;

class IstorijaDataCollector extends BaseDataCollector
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EventBus */
    private $eventBus;

    public function __construct(TraceableCommandBus $commandBus, EventBus $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
    }

    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $messageNormalizer = function (Message $message): array {
            return [
                'name' => ClassFunctions::fqcn($message),
                'payload' => $this->getPayloadFromMessage($message),
            ];
        };

        $this->data = [
            'commands' => $this->commandBus instanceof TraceableCommandBus
                ? array_map($messageNormalizer, $this->commandBus->getRecordedCommands())
                : [],
            'events' => $this->eventBus instanceof TraceableEventBus
                ? array_map($messageNormalizer, $this->eventBus->getRecordedEvents())
                : [],
        ];
    }

    public function getCommands(): array
    {
        return $this->data['commands'];
    }

    public function getEvents(): array
    {
        return $this->data['events'];
    }

    public function getCommandCount(): int
    {
        return count($this->data['commands']);
    }

    public function getEventCount(): int
    {
        return count($this->data['events']);
    }

    public function reset(): void
    {
        $this->data = [];
    }

    private function getPayloadFromMessage(Message $message)
    {
        if (method_exists($message, 'toArray')) {
            return $this->cloneVar($message->toArray());
        }

        return $this->cloneVar($message);
    }

    public function getName(): string
    {
        return 'istorija';
    }
}
