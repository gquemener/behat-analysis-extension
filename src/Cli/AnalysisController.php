<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\Cli;

use Behat\Testwork\Cli\Controller;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnalysisController implements Controller
{
    /**
     * {@inheritdoc}
     */
    public function configure(SymfonyCommand $command)
    {
        $command->addOption('analyze', null, null, 'Perform an analysis of your scenarios');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('analyze')) {
            return;
        }

        return 1;
    }
}
