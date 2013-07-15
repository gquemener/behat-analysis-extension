<?php

namespace Behat\AnalysisExtension\Formatter;

use Behat\Behat\Formatter\ProgressFormatter;
use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\DataCollector\LoggerDataCollector;

class AnalysisFormatter extends ProgressFormatter
{
    public function afterSuite(SuiteEvent $event)
    {
        parent::afterSuite($event);

        $logger = $event->getLogger();
        $this->writeln("\n{+passed}Behat Steps Analysis{-passed}");
        $this->writeln('====================');
        $this->printMethodCalls($logger);
        $this->printMergeableMethods($logger);
    }

    private function printMethodCalls(LoggerDataCollector $logger)
    {
        $methodCalls = $logger->getMethodCalls();
        arsort($methodCalls);

        $onceCalled = array_filter($methodCalls, function ($count) {
            return 1 === $count;
        });
        $this->writeln(sprintf('{+passed}%d{-passed} steps were used once.', count($onceCalled)));
        reset($methodCalls);
        $this->writeln(sprintf(
            'The most used step is {+passed}%s{-passed} with {+passed}%d{-passed} calls.',
            key($methodCalls), current($methodCalls)
        ));
    }

    private function printMergeableMethods(LoggerDataCollector $logger)
    {
        $this->writeln('Some steps might be merged to reduce their implementation redundancy:');
        foreach ($logger->getMergeableMethods() as $method) {
            $this->writeln(sprintf(
                '  - {+undefined}%s{-undefined} and {+undefined}%s{-undefined}',
                $method['source'], $method['compare']
            ));
        }
    }
}

