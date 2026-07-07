<?php

declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Block;

use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template;
use ReactEdge\WidgetBridge\Model\Config;

class Widget extends Template
{
    private HttpContext $httpContext;

    public function __construct(
        Template\Context $context,
        HttpContext      $httpContext,
        private Config   $config,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
    }

    public function getWidgetId(): string
    {
        return (string) $this->getData('widget_id');
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled($this->getWidgetId());
    }

    public function getScriptUrl(): string
    {
        return $this->config->getScriptUrl($this->getWidgetId());
    }

    public function getContractUrl(): string
    {
        return $this->config->getContractUrl($this->getWidgetId());
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            $this->getIdentities(),
            $this->_storeManager->getStore()->getId(),
            (int)$this->_storeManager->getStore()->isCurrentlySecure(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH),
            $this->getTemplateFile(),
            'template' => $this->getTemplate()
        ];
    }

    public function getIdentities(): array
    {
        return [
            'REACTEDGE_WIDGET_' . $this->getWidgetId()
        ];
    }
}
