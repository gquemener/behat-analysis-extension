<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\DataCollector;

use Behat\Behat\Definition\DefinitionRepository;
use Behat\Behat\Definition\Definition;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Behat\Definition\DefinitionFinder;
use GildasQ\BehatAnalysisExtension\EventDispatcher\SuiteAnalysisEvent;
use GildasQ\BehatAnalysisExtension\EventDispatcher\StepAnalysisEvent;
use GildasQ\BehatAnalysisExtension\EventDispatcher\AnalysisEvents;

final class DefinitionsUsageCollector implements EventSubscriberInterface
{
    private $repository;
    private $definitionFinder;
    private $usages;

    public function __construct(DefinitionRepository $repository, DefinitionFinder $definitionFinder)
    {
        $this->repository = $repository;
        $this->definitionFinder = $definitionFinder;
        $this->usages = new \SplObjectStorage();
    }

    public static function getSubscribedEvents()
    {
        return [
            AnalysisEvents::BEFORE_SUITE_ANALYSIS => 'onBeforeSuiteAnalysis',
            AnalysisEvents::BEFORE_STEP_ANALYSIS => 'onBeforeStepAnalysis',
        ];
    }

    public function onBeforeSuiteAnalysis(SuiteAnalysisEvent $event)
    {
        $environment = $event->environment();
        foreach ($this->repository->getEnvironmentDefinitions($environment) as $definition) {
            $this->usages[$definition] = 0;
        }
    }

    public function onBeforeStepAnalysis(StepAnalysisEvent $event)
    {
        $environment = $event->environment();
        $feature = $event->feature();
        $step = $event->step();

        $result = $this->definitionFinder->findDefinition($environment, $feature, $step);
        if ($result->hasMatch()) {
            $def = $result->getMatchedDefinition();
            $this->usages[$def] = $this->usages[$def] + 1;
        }
    }

    public function unusedSteps()
    {
        $unused = [];
        foreach ($this->usages as $definition) {
            if (0 === $this->usages[$definition]) {
                $unused[] = $definition;
            }
        }

        return $unused;
    }
}
