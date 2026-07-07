<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\ViewModel;

use Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Minicart implements ArgumentInterface
{
    private LocaleResolver $localeResolver;

    public function __construct(
        LocaleResolver $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
    }

    public function getCurrentStoreLocale(): string
    {
        return $this->localeResolver->getLocale();
    }
}
