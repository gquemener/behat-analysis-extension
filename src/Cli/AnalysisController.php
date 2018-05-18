<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension\Cli;

use Behat\Testwork\Cli\Controller;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Behat\Testwork\Suite\SuiteRegistry;
use Behat\Testwork\Suite\Suite;
use Behat\Testwork\Environment\EnvironmentManager;
use Behat\Behat\Definition\DefinitionRepository;
use Behat\Testwork\Specification\SpecificationFinder;
use Behat\Behat\Definition\DefinitionFinder;
use GildasQ\BehatAnalysisExtension\Analyzer\RuntimeExercise;
use GildasQ\BehatAnalysisExtension\Analyzer\SuitesAnalyzer;

final class AnalysisController implements Controller
{
    private $registry;
    private $specificationFinder;
    private $environmentManager;
    private $definitionFinder;
    private $definitionRepository;

    public function __construct(
        SuiteRegistry $registry,
        SpecificationFinder $specificationFinder,
        SuitesAnalyzer $analyzer
    ) {
        $this->specificationFinder = $specificationFinder;
        $this->registry = $registry;
        $this->analyzer = $analyzer;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(SymfonyCommand $command)
    {
        $command->addOption('analyze', null, null, 'Perform an analysis of your scenarios');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('analyze')) {
            return;
        }

        $specs = $this->findSpecifications($input);
        $results = $this->analyzeSpecifications($specs);

        return $results->isPassed() ? 0 : 1;
    }

    private function findSpecifications(InputInterface $input)
    {
        return $this->findSuitesSpecifications($this->getAvailableSuites(), $input->getArgument('paths'));
    }

    private function findSuitesSpecifications($suites, $locator)
    {
        return $this->specificationFinder->findSuitesSpecifications($suites, $locator);
    }

    private function getAvailableSuites()
    {
        return $this->registry->getSuites();
    }

    private function analyzeSpecifications(array $specifications)
    {
        return $this->analyzer->analyze($specifications);
    }
}
