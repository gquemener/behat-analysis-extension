<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Behat\Behat\ApplicationFactory;
use Symfony\Component\Console\Input\StringInput;

final class CommandContext implements Context
{
    private $application;

    public function __construct()
    {
        $this->application = (new ApplicationFactory())->createApplication();
        $this->application->setAutoExit(false);
    }

    /**
     * @When I run the analysis
     */
    public function iRunTheAnalysis()
    {
        $input = new StringInput('--analyze');
        $output = new BufferedOutput();

        $exitCode = $this->application->run($input, $output);

        if (0 !== $exitCode) {
            echo $output->fetch();

            throw new \RuntimeException(sprintf(
                'command errored with exit code %d',
                $exitCode
            ));
        }
    }
}
