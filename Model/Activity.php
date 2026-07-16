<?php

declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model;

use Psr\Log\LoggerInterface;
use ReactEdge\WidgetBridge\Api\ActivityInterface;

class Activity implements ActivityInterface
{
    private ?string $currentOperation = null;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function startOperation(
        string $name,
        array $attributes = []
    ): self {
        $this->currentOperation = $name;

        $this->logger->info(
            sprintf('Operation started: %s', $name),
            $attributes
        );

        return $this;
    }

    public function failOperation(
        array $attributes = []
    ): void {
        $this->logger->error(
            sprintf(
                'Operation failed: %s',
                $this->currentOperation ?? 'unknown'
            ),
            $attributes
        );
    }

    public function addEvent(
        string $name,
        array $attributes = []
    ): void {
        $this->logger->info(
            sprintf(
                '[%s] %s',
                $this->currentOperation ?? 'event',
                $name
            ),
            $attributes
        );
    }

    public function startChildOperation(
        string $name,
        array $attributes = []
    ): self {
        $this->logger->info(
            sprintf(
                '[%s] Child operation started: %s',
                $this->currentOperation ?? 'root',
                $name
            ),
            $attributes
        );

        return $this;
    }

    public function recordEvent(
        string $serviceName,
        string $name,
        array $payload = []
    ): void {
        $this->logger->info(
            sprintf(
                '[%s] %s',
                $serviceName,
                $name
            ),
            $payload
        );
    }
}
