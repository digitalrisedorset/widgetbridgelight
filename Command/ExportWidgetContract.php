<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Command;

use Magento\Framework\Console\Cli;
use ReactEdge\WidgetBridge\Model\RegistryReader\AssetsReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Filesystem\Driver\File;

class ExportWidgetContract extends Command
{
    public const NAME = 'reactedge:contract:export';

    public function __construct(
        private AssetsReader    $assetsReader,
        private readonly File $fileDriver
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);

        $this->setDescription(
            'Export a ReactEdge widget contract.'
        );

        $this->addArgument(
            'widget',
            InputArgument::REQUIRED,
            'Widget identifier'
        );

        parent::configure();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $widgetId = (string) $input->getArgument('widget');

        $output->writeln("Exporting {$widgetId}...");

        try {
            $json = $this->assetsReader->getContract($widgetId);

            if (empty($json)) {
                $output->writeln("<error>FAILED to read the contract</error>");
                return Cli::RETURN_FAILURE;
            }

            $path = $this->assetsReader->getReactEdgeDebugDirectory()
                . $widgetId
                . '.json';

            $directory = dirname($path);

            if (!$this->fileDriver->isExists($directory)) {
                $this->fileDriver->createDirectory($directory);
            }

            $content = json_encode(
                $json['contract'],
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
            );
            $this->fileDriver->filePutContents($path, $content);

            $output->writeln('<info>OK</info>');
        } catch (\Throwable $e) {
            $output->writeln("<error>FAILED: {$e->getMessage()}</error>");
        }

        $output->writeln('<info>Done.</info>');

        return Cli::RETURN_SUCCESS;
    }
}
