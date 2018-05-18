<?php

namespace GildasQ\BehatAnalysisExtension\Compliance;

use GildasQ\BehatAnalysisExtension\Analyzer\StepAnalyzer;
use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;

final class RuleCheckingStepAnalyzer implements StepAnalyzer
{
    private $rules = [];

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(Environment $environment, FeatureNode $feature, StepNode $step)
    {
        $results = [];
        foreach ($this->rules as $rule) {
            $results[] = $rule->checkCompliance($step);
        }

        return 0;
    }
}
