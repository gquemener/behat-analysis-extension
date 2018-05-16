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
        EnvironmentManager $environmentManager,
        DefinitionFinder $definitionFinder,
        DefinitionRepository $definitionRepository
    ) {
        $this->specificationFinder = $specificationFinder;
        $this->registry = $registry;
        $this->environmentManager = $environmentManager;
        $this->definitionFinder = $definitionFinder;
        $this->definitionRepository = $definitionRepository;
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

        $suites = $this->registry->getSuites();
        $specs = $this->specificationFinder->findSuitesSpecifications($suites, $input->getArgument('paths'));
        $count = [];
        foreach ($specs as $spec) {
            $suite = $spec->getSuite();
            $environment = $this->environmentManager->buildEnvironment($suite);
            foreach ($this->definitionRepository->getEnvironmentDefinitions($environment) as $definition) {
                if (!isset($count[$definition->getPattern()])) {
                    $count[$definition->getPattern()] = 0;
                }
            }
            foreach ($spec as $feature) {
                foreach ($feature->getScenarios() as $scenario) {
                    foreach ($scenario->getSteps() as $step) {
                        $result = $this->definitionFinder->findDefinition($environment, $feature, $step);
                        if ($result->hasMatch()) {
                            ++$count[$result->getMatchedDefinition()->getPattern()];
                        }
                    }
                }
            }
        }

        $result = 0;
        $unusedSteps = array_filter($count, function($value) {
            return 0 === $value;
        });
        foreach (array_keys($unusedSteps) as $pattern) {
            $output->writeln(sprintf('%s is never used.', $pattern));
            $result = 1;
        }

        return $result;
    }
}
