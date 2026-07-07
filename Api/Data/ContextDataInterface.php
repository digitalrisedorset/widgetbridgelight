<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Api\Data;

/**
 * Interface ContextDataInterface
 *
 * Represents the immutable context snapshot exposed to widgets.
 */
interface ContextDataInterface
{
    /**
     * Masked quote ID for the current visitor, or null if no cart exists.
     *
     * @return string|null
     */
    public function getMaskedCartId(): ?string;

    /**
     * Indicates whether the user is logged in.
     *
     * @return bool
     */
    public function getIsLoggedIn(): bool;

    /**
     * Returns the customer ID if logged in, otherwise null.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Masked quote ID for the current visitor, or null if no cart exists.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Masked quote ID for the current visitor, or null if no cart exists.
     *
     * @return string
     */
    public function getLocale(): string;
}
