<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use Magento\Framework\App\CacheInterface;
use ReactEdge\WidgetBridge\Model\Cache;

class SsrCacheHandler
{
    private const CACHE_LIFETIME = 86400;

    public function __construct(
        private SiteViewModeReader $siteViewModeReader,
        private CacheInterface $cache
    ) {
    }

    public function loadCache(array $payload): ?string
    {
        $cacheKey = $this->generateCacheKey($payload);
        $cachedHtml = $this->cache->load($cacheKey);

        return $cachedHtml !== false ? $cachedHtml : null;
    }

    public function saveCache(array $payload, string $html): void
    {
        if ($html === '') {
            return;
        }

        $this->cache->save(
            $html,
            $this->generateCacheKey($payload),
            [Cache::CACHE_TAG],
            self::CACHE_LIFETIME
        );
    }

    private function generateCacheKey(array $payload): string
    {
        return sprintf(
            'reactedge_ssr_%s_%s',
            $payload['widgetId'] ?? 'unknown',
            hash('sha256', json_encode([
                'widget' => $payload['widget'] ?? null,
                'widgetId' => $payload['widgetId'] ?? null,
                'contractFile' => $payload['contractFile'] ?? null,
                'viewport' => $this->siteViewModeReader->getViewPort(),
            ]))
        );
    }
}
