<?php

declare(strict_types=1);

namespace ReactEdge\WidgetBridge\ViewModel;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use ReactEdge\WidgetBridge\Model\Config;
use ReactEdge\WidgetBridge\Model\Config\Runtime as RuntimeConfig;
use ReactEdge\WidgetBridge\Model\RegistryReader;
use ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

class Widget implements ArgumentInterface
{
    public function __construct(
        private Config                $config,
        private RegistryReader        $registryReader,
        private SsrRenderer           $ssrRenderer,
        private RuntimeConfig         $runtimeConfig,
        private SerializerInterface   $serializer
    ) {}

    public function isEnabled(mixed $widgetId): bool
    {
        return $this->config->isEnabled($widgetId);
    }

    public function getRuntimeRegistry(): array
    {
        $data = $this->registryReader->getWidgetsContract();
        return is_array($data) ? $data : [];
    }

    public function getWidgetSSRHtml(string $widgetId)
    {
        return $this->ssrRenderer->render($widgetId);
    }

    public function getRuntimeConfig()
    {
        $data = $this->runtimeConfig->getRuntimeConfig();
        return $this->serializer->serialize($data);
    }
}
