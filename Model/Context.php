<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Locale\Resolver;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Store\Model\StoreManagerInterface;
use ReactEdge\WidgetBridge\Model\Data\ContextData;

class Context implements \ReactEdge\WidgetBridge\Api\ContextInterface
{
    public function __construct(
        private StoreManagerInterface   $storeManager,
        private CustomerSession         $customerSession,
        private CheckoutSession         $checkoutSession,
        private QuoteIdMaskFactory      $quoteIdMaskFactory,
        private Resolver $localeResolver
    ) {}

    public function get(): \ReactEdge\WidgetBridge\Api\Data\ContextDataInterface
    {
        $store  = $this->storeManager->getStore();

        $isLoggedIn = $this->customerSession->isLoggedIn();
        $customerId = $isLoggedIn ? (int)$this->customerSession->getCustomerId() : null;

        $quote      = $this->checkoutSession->getQuote();
        $maskedCart = null;

        if ($quote && $quote->getId()) {
            if ($isLoggedIn) {
                // logged-in uses real cart ID
                $maskedCart = (string)$quote->getId();
            } else {
                // guest => masked cart ID
                $mask = $this->quoteIdMaskFactory->create()->load($quote->getId(), 'quote_id');
                $maskedCart = $mask->getMaskedId();
            }
        }

        return new ContextData(
            $store->getCurrentCurrencyCode(),
            str_replace('_', '-', $this->localeResolver->getLocale()),
            $maskedCart,
            $isLoggedIn,
            $customerId
        );
    }
}
