<?php

namespace GildasQ\BehatAnalysisExtension\Analyzer;

use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;

interface StepAnalyzer
{
    public function analyze(Environment $environment, FeatureNode $feature, StepNode $step);
}
