<?php

namespace GildasQ\BehatAnalysisExtension\Compliance;

use Behat\Gherkin\Node\ScenarioNode;
use GildasQ\BehatAnalysisExtension\Analyzer\ScenarioAnalyzer;
use GildasQ\BehatAnalysisExtension\Analyzer\StepAnalyzer;
use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;

final class RuleCheckingScenarioAnalyzer implements ScenarioAnalyzer
{
    private $analyzer;
    private $rules = [];

    public function __construct(StepAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(Environment $environment, FeatureNode $feature, ScenarioNode $scenario)
    {
        $results = [];
        foreach ($scenario->getSteps() as $step) {
            $this->analyzer->analyze($environment, $feature, $step);
        }

        $results = [];
        foreach ($this->rules as $rule) {
            $results[] = $rule->check();
        }

        return $results;
    }
}
