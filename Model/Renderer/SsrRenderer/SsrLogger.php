<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use _PHPStan_b22655c3f\Nette\Neon\Exception;
use Psr\Log\LoggerInterface;

class SsrLogger
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function catchError(\Throwable $e, string $widgetId)
    {
        $this->logger->critical('SSR exception', [
            'widgetId' => $widgetId,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    public function logResponse(int $status, string $body, array $payload)
    {
        $widgetId = $payload['widgetId'] ?? null;

        $this->logger->info('SSR response received', [
            'widgetId' => $widgetId,
            'status' => $status,
            'bodyLength' => strlen($body)
        ]);

        if ($status >= 400) {
            $this->logger->error('SSR request failed', [
                'widgetId' => $widgetId,
                'status' => $status,
                'response' => substr($body, 0, 2000),
                'payload' => json_encode($payload, JSON_PRETTY_PRINT)
            ]);

            return '';
        }
    }

    public function logSsrCall(array $data)
    {
        $this->logger->info('SSR request started', $data);
    }
}
