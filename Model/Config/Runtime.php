<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Config;

use Magento\Store\Model\StoreManagerInterface;

use ReactEdge\WidgetBridge\Model\Config;

class Runtime
{
    public function __construct(
        private Config                $config,
        private StoreManagerInterface $storeManager,
        private CategoryReader        $categoryReader,
        private ProductReader         $productReader

    ) {}

    public function getRuntimeConfig(): array
    {
        $data = [
            'integrations' => [
                'googleMaps' => [
                    "apiKey" => $this->getGoogleApiKey(),
                    "placeId" => $this->getGooglePlaceId(),
                ],
                "magentoGraphql" => $this->getGraphqlConfig()
            ],
            "context" => [
                "storeCode" => $this->storeManager->getStore()->getCode(),
                "category" => $this->categoryReader->getCurrentCategoryUrlKey(),
                "sku" => $this->productReader->getCurrentProductSku()
            ]
        ];

        return $data;
    }

    public function getGoogleApiKey()
    {
        return $this->config->getGoogleMapsApiKey();
    }

    public function getGooglePlaceId()
    {
        return $this->config->getGooglePlaceId();
    }

    public function getBaseUrl()
    {
        return $this->config->getBaseUrl();
    }

    private function getGraphqlConfig(): array
    {
        $graphqlConfig = [
            'api' => "{$this->getBaseUrl()}/graphql",
        ];

        return  $graphqlConfig;
    }
}
