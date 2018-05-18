<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use GildasQ\BehatAnalysisExtension\Analyzer\SuitesAnalyzer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatchingSuitesAnalyzer implements SuitesAnalyzer
{
    private $analyzer;
    private $eventDispatcher;

    public function __construct(
        SuitesAnalyzer $analyzer,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->analyzer = $analyzer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function analyze(array $iterators)
    {
        $results = $this->analyzer->analyze($iterators);

        $event = new SuitesAnalysisResultEvent($results);
        $this->eventDispatcher->dispatch(AnalysisEvents::AFTER_SUITES_ANALYSIS, $event);

        return $results;
    }
}
