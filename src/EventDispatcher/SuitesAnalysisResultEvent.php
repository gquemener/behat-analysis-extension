<?php

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use GildasQ\BehatAnalysisExtension\Compliance\Rule\AnalysisResults;

final class SuitesAnalysisResultEvent extends Event
{
    private $results;

    public function __construct(AnalysisResults $results)
    {
        $this->results = $results;
    }

    public function results()
    {
        return $this->results;
    }
}
