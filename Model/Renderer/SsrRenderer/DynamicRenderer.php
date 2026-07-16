<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use Laminas\ReCaptcha\Exception;
use ReactEdge\WidgetBridge\Api\ActivityInterface;

class DynamicRenderer
{

    public function __construct(
        private SsrApi             $ssrApi,
        private ActivityInterface  $activity,
        private SsrSnapshotStorage $snapshotStorage,
        private SsrCacheHandler $ssrCacheHandler
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
        ];

        $cachedHtml = $this->ssrCacheHandler->loadCache($payload);

        if ($cachedHtml !== null) {
            return $cachedHtml;
        }

        $ssrRequest = $this->activity->startChildOperation(
            'ssr.api.request',
            [
                'widget.id' => $widgetId,
                'contract.widget' => $contract->getWidget(),
                'contract.file' => $contract->getContractFile(),
            ]
        );

        try {
            $result = $this->ssrApi->requestSSR($ssrRequest, $payload);
            $this->ssrCacheHandler->saveCache($payload, $result);

            $ssr = is_string($result) ? $result : '';
            if ($result) {
                $html = $contract->getSsrCss() . $ssr;
            } else {
                throw new Exception('Could not generate a valid SSR content.');
            }

            $this->snapshotStorage->save(
                $render->getId(),
                $html
            );

            $this->activity->addEvent(
                'SSR Dynamic Render Completed',
                [
                    'css.length' => strlen($contract->getSsrCss()),
                    'ssr.length' => strlen($ssr),
                    'html.length' => strlen($html),
                    'snapshot.saved' => $render->getId() . '.html',
                ]
            );

            return $html;
        } catch (\Throwable $e) {
            $this->activity->failOperation(
                $ssrRequest,
                [
                    'exception.class' => get_class($e),
                    'exception.message' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }
}
