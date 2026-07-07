<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

final class ContractFactory
{
    public function create(array $data): Contract
    {
        return new Contract(
            $data['id'] ?? '',
            $data['widget'] ?? '',
            $data['contract'] ?? '',
            $data['contractFile'] ?? '',
            $data['ssr'] ?? null,
            $data['css'] ?? '',
            $data['src'] ?? ''
        );
    }
}
