<?php

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Specification\SpecificationIterator;
use GildasQ\BehatAnalysisExtension\Analyzer\StepAnalyzer;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\FeatureNode;
use GildasQ\BehatAnalysisExtension\EventDispatcher\AnalysisEvents;

final class EventDispatchingStepAnalyzer implements StepAnalyzer
{
    private $analyzer;
    private $eventDispatcher;

    public function __construct(StepAnalyzer $analyzer, EventDispatcherInterface $eventDispatcher)
    {
        $this->analyzer = $analyzer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function analyze(Environment $environment, FeatureNode $feature, StepNode $step)
    {
        $event = new StepAnalysisEvent($environment, $feature, $step);
        $this->eventDispatcher->dispatch(AnalysisEvents::BEFORE_STEP_ANALYSIS, $event);

        $this->analyzer->analyze($environment, $feature, $step);
    }
}
