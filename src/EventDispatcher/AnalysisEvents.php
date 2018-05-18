<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

final class AnalysisEvents
{
    const BEFORE_SUITE_ANALYSIS = 'suite_analysis.before' ;

    const BEFORE_STEP_ANALYSIS = 'step_analysis.before' ;

    const AFTER_SUITES_ANALYSIS = 'suites_analysis.after';
}
