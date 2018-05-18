<?php

namespace GildasQ\BehatAnalysisExtension\Analyzer;

use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Specification\SpecificationIterator;

interface SuiteAnalyzer
{
    public function analyze(Environment $environment, SpecificationIterator $iterator);
}
