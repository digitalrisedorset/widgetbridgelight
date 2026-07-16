<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use Magento\Framework\HTTP\Client\Curl;
use ReactEdge\WidgetBridge\Api\ActivityInterface;
use ReactEdge\WidgetBridge\Model\Config;
use ReactEdge\WidgetBridge\Model\Config\Runtime as RuntimeConfig;

class SsrApi
{

    public function __construct(
        private Curl $curl,
        private RuntimeConfig $runtime,
        private Config $config,
        private SsrLogger $ssrLogger,
        private ActivityInterface $activity,
        private SiteViewModeReader $siteViewModeReader
    ) {
    }

    /**
     * @param array $payload
     * @return string
     */
    public function requestSSR(
        array $payload
    ): string {
        $runtimeConfig = $this->runtime->getRuntimeConfig();
        $url = $this->config->getWidgetsSSREngineUrl() . '/render';

        $this->ssrLogger->logSsrCall([
            'url' => $url,
            'widgetId' => $payload['widgetId'] ?? null,
            'widget' => $payload['widget'] ?? null
        ]);

        $payload = array_merge(
            $payload,
            [
                'runtimeConfig' => $runtimeConfig ?? []
            ]
        );

        $this->curl->addHeader(
            'Content-Type',
            'application/json'
        );

        $this->curl->addHeader(
            'User-Agent',
            $this->siteViewModeReader->getViewPort()
        );

        $this->curl->post(
            $this->config->getWidgetsSSREngineUrl(). '/render',
            json_encode($payload)
        );

        $status = $this->curl->getStatus();
        $body = $this->curl->getBody();
        $headers = $this->curl->getHeaders();

        $this->activity->addEvent(
            'ssr.api.cache',
            [
                'cache' => $headers['x-ssr-cache']?? null,
            ]
        );

        $this->ssrLogger->logResponse($status, $body, $payload);

        return $body;
    }
}
