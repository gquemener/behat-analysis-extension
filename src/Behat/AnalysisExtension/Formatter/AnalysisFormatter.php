<?php

namespace Behat\AnalysisExtension\Formatter;

use Behat\Behat\Formatter\ProgressFormatter;
use Behat\Behat\Event\SuiteEvent;
use Behat\AnalysisExtension\DataCollector\AnalysisDataCollector;

class AnalysisFormatter extends ProgressFormatter
{
    public function afterSuite(SuiteEvent $event)
    {
        parent::afterSuite($event);

        $logger = $event->getLogger();
        $this->writeln("\n{+passed}Behat Steps Analysis{-passed}");
        $this->writeln('====================');
        $this->printStepsUsages($logger);
        $this->printMergeableSteps($logger);
    }

    private function printStepsUsages(AnalysisDataCollector $logger)
    {
        $stepsUsages = $logger->getStepsUsages();
        arsort($stepsUsages);

        $onceCalled = array_filter($stepsUsages, function ($count) {
            return 1 === $count;
        });
        $this->writeln(sprintf('{+passed}%d{-passed} steps were used once.', count($onceCalled)));
        reset($stepsUsages);
        $this->writeln(sprintf(
            'The most used step is {+passed}%s{-passed} with {+passed}%d{-passed} calls.',
            key($stepsUsages), current($stepsUsages)
        ));
    }

    private function printMergeableSteps(AnalysisDataCollector $logger)
    {
        $this->writeln('Some steps might be merged to reduce their implementation redundancy:');
        foreach ($logger->getMergeableSteps() as $step) {
            $this->writeln(sprintf(
                '  - {+undefined}%s{-undefined} and {+undefined}%s{-undefined}',
                $step['source'], $step['compare']
            ));
        }
    }
}

