<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Behat\Behat\ApplicationFactory;
use Symfony\Component\Console\Input\StringInput;

final class AnalysisContext implements Context
{
    private $projectDir;
    private $application;

    public function __construct()
    {
        $tempFile = tempnam(sys_get_temp_dir(), '');
        if (!is_string($tempFile)) {
            throw new \RuntimeException('Unable to generate unique filename');
        }

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        mkdir($tempFile);
        $this->projectDir = $tempFile;

        $this->application = (new ApplicationFactory())->createApplication();
        $this->application->setAutoExit(false);
    }

    /**
     * @Given the following :filename file:
     */
    public function theFollowingFile($filename, PyStringNode $data)
    {
        $filename = $this->getPrefixedPath($filename);
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($filename, (string) $data);
    }

    /**
     * @When I run the analysis
     */
    public function iRunTheAnalysis()
    {
        $input = new StringInput(sprintf('--analyze --config %s', $this->getPrefixedPath('behat.yaml')));
        $this->output = new BufferedOutput();

        $this->application->run($input, $this->output);
    }
    /**
     * @Then I should see the :path step as unused.
     */
    public function iShouldSeeTheStepAsUnused($path)
    {
        $output = $this->output->fetch();
        $expected = sprintf('%s is never used', $path);

        if (false === strpos($output, $expected)) {
            echo $output;
            throw new \RuntimeException(sprintf('Output does not contain "%s".', $expected));
        }
    }

    private function getPrefixedPath($filename)
    {
        return sprintf('%s/%s', $this->projectDir, $filename);
    }
}
