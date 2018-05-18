<?php

namespace GildasQ\BehatAnalysisExtension\Analyzer;

use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;

interface FeatureAnalyzer
{
    public function analyze(Environment $environment, FeatureNode $feature);
}
