<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use ReactEdge\WidgetBridge\Api\ActivityInterface;

class StaticRenderer
{
    public function __construct(
        private ActivityInterface $activity,
        private SiteViewModeReader $siteViewModeReader
    ) {
    }

    public function render(
        Contract $contract

    ): string {
        $css = $contract->getSsrCss();
        $html = $css . $contract->getSsrHtml(
            $this->siteViewModeReader->getViewPort()
            );

        $this->activity->addEvent(
            'SSR Static Render Completed',
            [
                'css.length' => strlen($css),
                'ssr.length' => strlen($html)
            ]
        );

        return $html;
    }
}
