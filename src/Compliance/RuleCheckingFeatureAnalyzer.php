<?php

namespace GildasQ\BehatAnalysisExtension\Compliance;

use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;
use GildasQ\BehatAnalysisExtension\Analyzer\FeatureAnalyzer;
use GildasQ\BehatAnalysisExtension\Analyzer\ScenarioAnalyzer;

final class RuleCheckingFeatureAnalyzer implements FeatureAnalyzer
{
    private $analyzer;
    private $rules = [];

    public function __construct(ScenarioAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(Environment $environment, FeatureNode $feature)
    {
        foreach ($feature->getScenarios() as $scenario) {
            $this->analyzer->analyze($environment, $feature, $scenario);
        }

        $results = [];
        foreach ($this->rules as $rule) {
            $results[] = $rule->check();
        }

        return $results;
    }
}
