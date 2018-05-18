<?php

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use GildasQ\BehatAnalysisExtension\Analyzer\SuiteAnalyzer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Specification\SpecificationIterator;
use GildasQ\BehatAnalysisExtension\EventDispatcher\AnalyzerEvent;

final class EventDispatchingSuiteAnalyzer implements SuiteAnalyzer
{
    private $analyzer;
    private $eventDispatcher;

    public function __construct(SuiteAnalyzer $analyzer, EventDispatcherInterface $eventDispatcher)
    {
        $this->analyzer = $analyzer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function analyze(Environment $environment, SpecificationIterator $iterator)
    {
        $event = new SuiteAnalysisEvent($environment);
        $this->eventDispatcher->dispatch(AnalysisEvents::BEFORE_SUITE_ANALYSIS, $event);

        $this->analyzer->analyze($environment, $iterator);
    }
}
