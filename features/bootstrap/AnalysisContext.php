<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

class AnalysisContext implements Context
{
    /**
     * @Then I should see the :arg1 step as unused.
     */
    public function iShouldSeeTheStepAsUnused($arg1)
    {
        throw new PendingException();
    }
}
