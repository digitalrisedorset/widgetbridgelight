<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\RegistryReader;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use ReactEdge\WidgetBridge\Model\Cache;
use ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer\SiteViewModeReader;

class AssetsCacheHandler
{
    private const CACHE_LIFETIME = 86400;

    public function __construct(
        private CacheInterface $cache,
        private StoreManagerInterface $storeManager,
        private SerializerInterface   $serializer
    ) {
    }

    public function loadCache(string $assetPath): ?array
    {
        $cacheKey = $this->generateCacheKey($assetPath);
        $cachedHtml = $this->cache->load($cacheKey);

        return $cachedHtml !== false ? $this->serializer->unserialize($cachedHtml) : null;
    }

    public function saveCache(string $assetPath, array $contents): void
    {
        $this->cache->save(
            $this->serializer->serialize($contents),
            $this->generateCacheKey($assetPath),
            [Cache::CACHE_TAG],
            self::CACHE_LIFETIME
        );
    }

    private function generateCacheKey(string $assetPath): string
    {
        return sprintf(
            'reactedge_asset_%s',
            hash(
                'sha256',
                $this->serializer->serialize([
                    'store' => $this->storeManager->getStore()->getCode(),
                    'asset-path' => $assetPath
                ])
            )
        );
    }
}
