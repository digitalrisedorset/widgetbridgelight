<?php

namespace ReactEdge\WidgetBridge\Api\Data;

interface WidgetRenderPayloadInterface
{
    public function getWidgetId(): string;

    public function getContract(): array;

    public function getContractFile(): ?string;

    public function getRenderId(): string;
}
