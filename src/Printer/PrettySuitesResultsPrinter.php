<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\Printer;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use GildasQ\BehatAnalysisExtension\EventDispatcher\AnalysisEvents;
use GildasQ\BehatAnalysisExtension\EventDispatcher\SuitesAnalysisResultEvent;
use GildasQ\BehatAnalysisExtension\Compliance\Rule\AnalysisResult;
use Symfony\Component\Console\Output\OutputInterface;

final class PrettySuitesResultsPrinter implements EventSubscriberInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public static function getSubscribedEvents()
    {
        return [
            AnalysisEvents::AFTER_SUITES_ANALYSIS => 'onAfterSuitesAnalysis',
        ];
    }

    public function onAfterSuitesAnalysis(SuitesAnalysisResultEvent $event)
    {
        foreach ($event->results() as $result) {
            if ($result->isPassed()) {
                continue;
            }

            $this->printResult($result);
        }
    }

    private function printResult(AnalysisResult $result)
    {
        foreach ($result->reasons() as $reason) {
            $this->output->writeln($reason);
        }
    }
}
