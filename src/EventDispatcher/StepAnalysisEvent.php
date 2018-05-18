<?php

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;

final class StepAnalysisEvent extends Event
{
    private $environment;
    private $feature;
    private $step;

    public function __construct(Environment $environment, FeatureNode $feature, StepNode $step)
    {
        $this->environment = $environment;
        $this->feature = $feature;
        $this->step = $step;
    }

    public function environment()
    {
        return $this->environment;
    }

    public function feature()
    {
        return $this->feature;
    }

    public function step()
    {
        return $this->step;
    }
}
