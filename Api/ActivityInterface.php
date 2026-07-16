<?php

namespace ReactEdge\WidgetBridge\Api;

interface ActivityInterface
{
    /**
     * Starts a business operation.
     *
     * The returned Operation should be completed using
     * endOperation() or failOperation().
     *
     * @param string $name Operation name.
     * @param array<string,mixed> $attributes Initial operation attributes.
     */
    public function startOperation(
        string $name,
        array $attributes = []
    );

    /**
     * Marks an operation as failed.
     *
     * @param array<string,mixed> $attributes Failure attributes.
     */
    public function failOperation(
        array $attributes = []
    ): void;

    /**
     * Records an event against an existing operation.
     *
     * Events provide additional context within an operation
     * without creating additional spans.
     */
    public function addEvent(
        string $name,
        array $attributes = []
    ): void;

    public function startChildOperation(
        string $name,
        array $attributes = []
    );

    /**
     * Records a standalone telemetry event.
     *
     * This method creates an independent span with no parent
     * operation.
     *
     * Prefer Operations whenever the activity being measured
     * has a start and end lifecycle.
     *
     * Excessive use of standalone events may lead to fragmented
     * traces in observability backends such as Jaeger.
     *
     * Suitable use cases include:
     * - Application startup
     * - Configuration reporting
     * - One-off lifecycle notifications
     * - Diagnostic instrumentation
     */
    public function recordEvent(
        string $serviceName,
        string $name,
        array $payload = []
    ): void;
}
