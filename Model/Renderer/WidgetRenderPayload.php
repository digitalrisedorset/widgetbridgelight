<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer;

use ReactEdge\WidgetBridge\Api\Data\WidgetRenderPayloadInterface;

class WidgetRenderPayload implements WidgetRenderPayloadInterface
{
    public function __construct(
        private string $widgetId,
        private array $contract,
        private ?string $contractFile,
        private string $renderId,
        private int $generatedAt
    ) {}

    public function getWidgetId(): string
    {
        return $this->widgetId;
    }

    public function getContract(): array
    {
        return $this->contract;
    }

    public function getContractFile(): ?string
    {
        return $this->contractFile;
    }

    public function getRenderId(): string
    {
        return $this->renderId;
    }

    public function getGeneratedAt(): int
    {
        return $this->generatedAt;
    }
}
