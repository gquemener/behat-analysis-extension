<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\Analyzer;

interface SuitesAnalyzer
{
    public function analyze(array $iterators);
}
