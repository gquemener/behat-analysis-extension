<?php

namespace GildasQ\BehatAnalysisExtension\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use Behat\Testwork\Environment\Environment;

final class SuiteAnalysisEvent extends Event
{
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function environment()
    {
        return $this->environment;
    }
}
