<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

use ReactEdge\WidgetBridge\Api\ActivityInterface;
use ReactEdge\WidgetBridge\Model\RegistryReader;

class ContractValidator
{

    public function __construct(
        private RegistryReader     $registryReader,
        private ActivityInterface $activity,
        private ContractFactory $contractFactory
    ) {
    }

    /**
     * @param string $widgetId
     * @return Contract|null
     */
    public function validate(
        string $widgetId,
    ): ?Contract
    {
        $json = $this->registryReader->getWidgetContract($widgetId);

        if (empty($json)) {
            return null;
        }

        try {
            $contract = $this->contractFactory->create($json);
        } catch (\Throwable $exception) {
            $this->activity->failOperation(
                [
                    'ssr.invalid' => true,
                    'widget.id' => $widgetId,
                    'json' => $json
                ]
            );

            return null;
        }

        $this->activity->addEvent(
            'Contract Loaded',
            [
                'contract.id' => $contract->getId(),
                'contract.widget' => $contract->getWidget(),
            ]
        );

        $this->activity->addEvent(
            'SSR Strategy Selected',
            [
                'widget' => $contract->getWidget(),
                'strategy' => $contract->getRenderingStrategy(),
                'ssr' => $contract->getSsrHtml(),
                'css' => $contract->getSsrCss()
            ]
        );

        return $contract;
    }
}
