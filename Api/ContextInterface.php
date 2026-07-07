<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Api;

interface ContextInterface
{
    /**
     * @return \ReactEdge\WidgetBridge\Api\Data\ContextDataInterface
     */
    public function get(): \ReactEdge\WidgetBridge\Api\Data\ContextDataInterface;
}
