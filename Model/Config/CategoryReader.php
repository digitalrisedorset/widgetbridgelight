<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Config;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Category;

class CategoryReader
{
    public function __construct(
        private readonly Resolver $layerResolver
    ) {
    }

    public function getCurrentCategoryUrlKey(): ?string
    {
        try {
            $category = $this->layerResolver
                ->get()
                ->getCurrentCategory();

            if (!$category instanceof Category) {
                return null;
            }

            return $category->getUrlKey();

        } catch (\Throwable) {
            return null;
        }
    }
}
