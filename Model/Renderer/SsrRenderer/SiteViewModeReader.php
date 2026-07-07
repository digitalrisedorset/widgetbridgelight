<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use Magento\Framework\App\RequestInterface;

class SiteViewMode
{
    public const DESKTOP = 'desktop';
    public const MOBILE = 'mobile';
    public const TABLET = 'tablet';
}

class SiteViewModeReader
{
    public function __construct(
        private RequestInterface $request
    ) {
    }

    public function getViewPort(): string
    {
        $userAgent = strtolower(
            $this->request->getServer(
                'HTTP_USER_AGENT'
            ) ?? ''
        );

        if (
            str_contains($userAgent, 'ipad')
            || str_contains($userAgent, 'tablet')
        ) {
            return 'tablet';
        }

        if (
            str_contains($userAgent, 'mobile')
            || str_contains($userAgent, 'android')
            || str_contains($userAgent, 'iphone')
        ) {
            return 'mobile';
        }

        return 'desktop';
    }
}
