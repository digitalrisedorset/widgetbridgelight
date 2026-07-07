<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Data;

use ReactEdge\WidgetBridge\Api\Data\ContextDataInterface;

class ContextData implements ContextDataInterface
{
    public function __construct(
        private string  $currency,
        private string  $locale,
        private ?string $maskedCartId,
        private bool    $isLoggedIn,
        private ?int    $customerId
    ) {}

    public function getMaskedCartId(): ?string
    {
        return $this->maskedCartId;
    }
    public function getIsLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
