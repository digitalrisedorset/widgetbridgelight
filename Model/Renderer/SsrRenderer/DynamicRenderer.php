<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use Laminas\ReCaptcha\Exception;
use ReactEdge\WidgetBridge\Api\ActivityInterface;
use ReactEdge\WidgetBridge\Model\Config\Runtime as RuntimeConfig;

class DynamicRenderer
{

    public function __construct(
        private SsrApi             $ssrApi,
        private ActivityInterface  $activity,
        private SsrCacheHandler $ssrCacheHandler,
        private RuntimeConfig      $runtimeConfig,
    ) {
    }

    public function render(
        Contract $contract,
        string $widgetId
    ): string {
        $payload = [
            'widgetId' => $widgetId,
            'widget' => $contract->getWidget(),
            'contract' => $contract->getContract(),
            'contractFile' => $contract->getContractFile(),
            'runtimeConfig' => $this->runtimeConfig->getRuntimeConfig()
        ];

        $cachedHtml = $this->ssrCacheHandler->loadCache($payload);

        if ($cachedHtml !== null) {
           return $cachedHtml;
        }

        try {
            $result = $this->ssrApi->requestSSR($payload);
            $this->ssrCacheHandler->saveCache($payload, $result);

            $ssr = is_string($result) ? $result : '';
            if ($result) {
                $html = $contract->getSsrCss() . $ssr;
            } else {
                throw new Exception('Could not generate a valid SSR content.');
            }

            $this->activity->addEvent(
                'SSR Dynamic Render Completed',
                [
                    'css.length' => strlen($contract->getSsrCss()),
                    'ssr.length' => strlen($ssr),
                    'html.length' => strlen($html)
                ]
            );

            return $html;
        } catch (\Throwable $e) {
            $this->activity->failOperation(
                [
                    'exception.class' => get_class($e),
                    'exception.message' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}
