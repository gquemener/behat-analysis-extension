Feature: Detect unused steps
    In order to keep a curated list of usefull steps
    As a developer
    I need to be able to know which steps defined in my contexts are never used

    Scenario: Successfully detect an unused step in my test suite
        Given the following "behat.yaml" file:
        """
        default:
            extensions:
                GildasQ\BehatAnalysisExtension\AnalysisExtension: ~
        """
        Given the following "features/some_functionnality.feature" file:
        """
        Feature:
            Scenario: Successfully do something
                Given I have done that
                When I do something
                Then I should see some output
        """
        And the following "feature/Context/FeatureContext.php" file:
        """
        <?php

        use Behat\Behat\Context\Context;
        use Behat\Gherkin\Node\PyStringNode;
        use Behat\Gherkin\Node\TableNode;

        class FeatureContext implements Context
        {
            /**
             * @Given I have done that
             */
            public function iHaveDoneThat()
            {
            }

            /**
             * @When I do something
             */
            public function iDoSomething()
            {
            }

            /**
             * @Then I should see some output
             */
            public function iShouldSeeSomeOutput()
            {
            }

            /**
             * @Then I should grab a coffee
             */
            public function iShouldGrabACoffee()
            {
            }
        }
        """
        When I run the analysis
        Then I should see the "I should grab a coffee" step as unused.
