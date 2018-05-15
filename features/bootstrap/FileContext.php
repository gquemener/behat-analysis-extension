<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

final class FileContext implements Context
{
    private $projectDir;

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

    private function getPrefixedPath($filename)
    {
        return sprintf('%s/%s', $this->projectDir, $filename);
    }
}
