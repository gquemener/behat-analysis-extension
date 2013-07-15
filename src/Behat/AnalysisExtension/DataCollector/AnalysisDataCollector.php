<?php

namespace Behat\AnalysisExtension\DataCollector;

use Behat\Behat\DataCollector\LoggerDataCollector;
use Behat\Behat\Definition\Annotation\Definition;
use Behat\Behat\Event\StepEvent;
use Behat\Behat\Event\SuiteEvent;

class AnalysisDataCollector extends LoggerDataCollector
{
    protected $stepsUsages    = array();
    protected $mergeableSteps = array();

    private $comparedMethods = array();


    public function getStepsUsages()
    {
        return $this->stepsUsages;
    }

    public function getMergeableSteps()
    {
        return $this->mergeableSteps;
    }

    public function afterStep(StepEvent $event)
    {
        parent::afterStep($event);

        $definition = $event->getDefinition();
        if ($definition instanceof Definition) {
            $this->incrementMethodCall($definition->getRegex());
        }
    }

    public function afterSuite(SuiteEvent $event)
    {
        foreach ($this->stepsUsages as $source => $count) {
            foreach ($this->stepsUsages as $compare => $count) {
                if ($this->haveBeenCompared($source, $compare)) {
                    continue;
                }
                $score = $this->compareMethods($source, $compare);
                if ($score > 0 && $score <= 0.2) {
                    $this->mergeableSteps[] = array(
                        'source'  => $source,
                        'compare' => $compare,
                    );
                }
            }
        }
    }

    private function haveBeenCompared($a, $b)
    {
        if (isset($this->comparedMethods[$a]) && in_array($b, $this->comparedMethods[$a])) {
            return true;
        }

        if (isset($this->comparedMethods[$b]) && in_array($a, $this->comparedMethods[$b])) {
            return true;
        }

        return false;
    }

    private function compareMethods($a, $b)
    {
        $this->comparedMethods[$a][] = $b;
        $this->comparedMethods[$b][] = $a;

        return levenshtein($a, $b)/strlen($a);
    }

    private function incrementMethodCall($methodPath)
    {
        if (!isset($this->stepsUsages[$methodPath])) {
            $this->stepsUsages[$methodPath] = 1;
        } else {
            $this->stepsUsages[$methodPath]++;
        }
    }
}
