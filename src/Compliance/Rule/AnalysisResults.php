<?php

namespace GildasQ\BehatAnalysisExtension\Compliance\Rule;

final class AnalysisResults implements \IteratorAggregate
{
    private $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function isPassed()
    {
        foreach ($this->results as $result) {
            if (!$result->isPassed()) {
                return false;
            }
        }

        return true;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->results);
    }
}
