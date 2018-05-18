<?php

namespace GildasQ\BehatAnalysisExtension\Analyzer;

use Behat\Gherkin\Node\ScenarioNode;
use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;

interface ScenarioAnalyzer
{
    public function analyze(Environment $environment, FeatureNode $feature, ScenarioNode $scenario);
}
