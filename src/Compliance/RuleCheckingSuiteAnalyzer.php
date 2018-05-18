<?php

namespace GildasQ\BehatAnalysisExtension\Compliance;

use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Specification\SpecificationIterator;
use GildasQ\BehatAnalysisExtension\Analyzer\SuiteAnalyzer;
use GildasQ\BehatAnalysisExtension\Analyzer\FeatureAnalyzer;

final class RuleCheckingSuiteAnalyzer implements SuiteAnalyzer
{
    private $analyzer;
    private $rules = [];

    public function __construct(FeatureAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(Environment $environment, SpecificationIterator $iterator)
    {
        foreach ($iterator as $feature) {
            $this->analyzer->analyze($environment, $feature);
        }

        $results = [];
        foreach ($this->rules as $rule) {
            $results[] = $rule->check();
        }

        return $results;
    }
}
