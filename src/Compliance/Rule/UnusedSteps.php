<?php

namespace GildasQ\BehatAnalysisExtension\Compliance\Rule;

use GildasQ\BehatAnalysisExtension\DataCollector\DefinitionsUsageCollector;
use Behat\Testwork\Output\Printer\StreamOutputPrinter;

final class UnusedSteps
{
    private $collector;

    public function __construct(DefinitionsUsageCollector $collector)
    {
        $this->collector = $collector;
    }

    public function check()
    {
        $reasons = [];
        foreach ($this->collector->unusedSteps() as $step) {
            $reasons[] = sprintf('  - "%s" is never used (%s)', $step->getPattern(), $step->getPath());
        }

        if (count($reasons)) {
            return AnalysisResult::fail($reasons);
        }

        return AnalysisResult::pass();
    }
}
