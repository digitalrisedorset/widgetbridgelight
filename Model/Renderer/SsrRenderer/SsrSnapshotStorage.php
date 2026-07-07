<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

class SsrSnapshotStorage
{
    public function save(
        string $messageId,
        string $content
    ): string {
        $directory = BP . '/pub/reactedge-ssr/';

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        file_put_contents(
            $directory . '/' . $messageId . '.html',
            $content
        );

        return $messageId;
    }
}
