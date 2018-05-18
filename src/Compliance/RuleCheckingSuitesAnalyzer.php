<?php

namespace GildasQ\BehatAnalysisExtension\Compliance;

use Behat\Testwork\Specification\GroupedSpecificationIterator;
use Behat\Testwork\Environment\EnvironmentManager;
use GildasQ\BehatAnalysisExtension\Analyzer\SuitesAnalyzer;
use GildasQ\BehatAnalysisExtension\Analyzer\SuiteAnalyzer;
use GildasQ\BehatAnalysisExtension\Compliance\Rule\AnalysisResults;

final class RuleCheckingSuitesAnalyzer implements SuitesAnalyzer
{
    private $envManager;
    private $suiteAnalyzer;
    private $rules = [];

    public function __construct(EnvironmentManager $envManager, SuiteAnalyzer $suiteAnalyzer)
    {
        $this->envManager = $envManager;
        $this->suiteAnalyzer = $suiteAnalyzer;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(array $iterators)
    {
        foreach (GroupedSpecificationIterator::group($iterators) as $iterator) {
            $environment = $this->envManager->buildEnvironment($iterator->getSuite());
            $this->suiteAnalyzer->analyze($environment, $iterator);
        }

        $results = [];
        foreach ($this->rules as $rule) {
            $results[] = $rule->check();
        }

        return new AnalysisResults($results);
    }
}
