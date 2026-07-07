<?php
declare(strict_types=1);

namespace ReactEdge\WidgetBridge\Command;

use Magento\Framework\Console\Cli;
use ReactEdge\WidgetBridge\Model\RegistryReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ReactEdge\WidgetBridge\Model\Renderer\SsrRenderer;

class GenerateSsrCommand extends Command
{
    public const NAME = 'reactedge:ssr:generate';

    public function __construct(
        private readonly SsrRenderer $ssrRenderer,
        private readonly RegistryReader $registryReader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription(
            'Generate the ReactEdge SSR cache.'
        );

        parent::configure();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $output->writeln('Generating SSR...');

        foreach ($this->registryReader->getMainActiveWidgets() as $widgetId) {
            if ($widgetId === 'intentdiscovery') continue;
            
            $output->write("Generating {$widgetId}... ");

            try {
                $this->ssrRenderer->render($widgetId);
                $output->writeln('<info>OK</info>');
            } catch (\Throwable $e) {
                $output->writeln("<error>FAILED: {$e->getMessage()}</error>");
            }
        }

        $output->writeln('<info>Done.</info>');

        return Cli::RETURN_SUCCESS;
    }
}
