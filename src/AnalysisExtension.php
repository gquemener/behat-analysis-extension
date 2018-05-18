<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension;

use Behat\Testwork\ServiceContainer\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Testwork\Cli\ServiceContainer\CliExtension;
use Symfony\Component\DependencyInjection\Reference;
use Behat\Testwork\Suite\ServiceContainer\SuiteExtension;
use Behat\Testwork\Environment\ServiceContainer\EnvironmentExtension;
use Behat\Behat\Definition\ServiceContainer\DefinitionExtension;
use Behat\Testwork\Specification\ServiceContainer\SpecificationExtension;
use Behat\Testwork\ServiceContainer\ServiceProcessor;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

final class AnalysisExtension implements Extension
{
    const SUITES_ANALYZER_ID = 'analysis.suites_analyzer';
    const SUITE_ANALYZER_ID = 'analysis.suite_analyzer';
    const FEATURE_ANALYZER_ID = 'analysis.feature_analyzer';
    const SCENARIO_ANALYZER_ID = 'analysis.scenario_analyzer';
    const STEP_ANALYZER_ID = 'analysis.step_analyzer';
    const DEFINITIONS_USAGE_COLLECTOR_ID = 'analysis.definitions_usage_collector';
    const PRETTY_SUITES_RESULTS_PRINTER = 'analysis.pretty_suites_results_printer';

    private $processor;

    public function __construct(ServiceProcessor $processor = null)
    {
        $this->processor = $processor ?: new ServiceProcessor();
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function getConfigKey()
    {
        return 'analysis';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadComplianceRules($container);
        $this->loadCollector($container);
        $this->loadPrinter($container);
        $this->loadStepAnalyzer($container);
        $this->loadScenarioAnalyzer($container);
        $this->loadFeatureAnalyzer($container);
        $this->loadSuiteAnalyzer($container);
        $this->loadSuitesAnalyzer($container);
        $this->loadController($container);
    }

    private function loadPrinter(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Printer\PrettySuitesResultsPrinter', [
            new Reference(CliExtension::OUTPUT_ID)
        ]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG);

        $container->setDefinition(self::PRETTY_SUITES_RESULTS_PRINTER, $definition);
    }

    private function loadCollector(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\DataCollector\DefinitionsUsageCollector', [
            new Reference(DefinitionExtension::REPOSITORY_ID),
            new Reference(DefinitionExtension::FINDER_ID),
        ]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG);
        $container->setDefinition(self::DEFINITIONS_USAGE_COLLECTOR_ID, $definition);
    }

    private function loadComplianceRules(ContainerBuilder $container)
    {
        $rules = [
            self::SUITES_ANALYZER_ID => [
                'GildasQ\BehatAnalysisExtension\Compliance\Rule\UnusedSteps' => [
                    self::DEFINITIONS_USAGE_COLLECTOR_ID,
                ]
            ],
            self::SUITE_ANALYZER_ID => [],
            self::FEATURE_ANALYZER_ID => [],
            self::SCENARIO_ANALYZER_ID => [],
            self::STEP_ANALYZER_ID => [],
        ];

        foreach ($rules as $analyzer => $classes) {
            foreach ($classes as $class => $arguments) {
                $definition = new Definition($class, array_map(function(string $id) {
                    return new Reference($id);
                }, $arguments));
                $definition->addTag($analyzer . '.rule');
                $container->setDefinition($analyzer . '.' . $class, $definition);
            }
        }
    }

    private function loadStepAnalyzer(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Compliance\RuleCheckingStepAnalyzer');

        $references = $this->processor->findAndSortTaggedServices($container, self::STEP_ANALYZER_ID . '.rule');
        $definition->addMethodCall('setRules', [$references]);

        $container->setDefinition(self::STEP_ANALYZER_ID . '.inner', $definition);

        $definition = new Definition('GildasQ\BehatAnalysisExtension\EventDispatcher\EventDispatchingStepAnalyzer', [
            new Reference(self::STEP_ANALYZER_ID . '.inner'),
            new Reference(EventDispatcherExtension::DISPATCHER_ID),
        ]);

        $container->setDefinition(self::STEP_ANALYZER_ID, $definition);
    }

    private function loadScenarioAnalyzer(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Compliance\RuleCheckingScenarioAnalyzer', [
            new Reference(self::STEP_ANALYZER_ID),
        ]);

        $references = $this->processor->findAndSortTaggedServices($container, self::SCENARIO_ANALYZER_ID . '.rule');
        $definition->addMethodCall('setRules', [$references]);

        $container->setDefinition(self::SCENARIO_ANALYZER_ID, $definition);
    }

    private function loadFeatureAnalyzer(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Compliance\RuleCheckingFeatureAnalyzer', [
            new Reference(self::SCENARIO_ANALYZER_ID),
        ]);

        $references = $this->processor->findAndSortTaggedServices($container, self::FEATURE_ANALYZER_ID . '.rule');
        $definition->addMethodCall('setRules', [$references]);

        $container->setDefinition(self::FEATURE_ANALYZER_ID, $definition);
    }

    private function loadSuiteAnalyzer(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Compliance\RuleCheckingSuiteAnalyzer', [
            new Reference(self::FEATURE_ANALYZER_ID),
        ]);

        $references = $this->processor->findAndSortTaggedServices($container, self::SUITE_ANALYZER_ID . '.rule');
        $definition->addMethodCall('setRules', [$references]);

        $container->setDefinition(self::SUITE_ANALYZER_ID . '.inner', $definition);

        $definition = new Definition('GildasQ\BehatAnalysisExtension\EventDispatcher\EventDispatchingSuiteAnalyzer', [
            new Reference(self::SUITE_ANALYZER_ID . '.inner'),
            new Reference(EventDispatcherExtension::DISPATCHER_ID),
        ]);

        $container->setDefinition(self::SUITE_ANALYZER_ID, $definition);
    }

    private function loadSuitesAnalyzer(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Compliance\RuleCheckingSuitesAnalyzer', [
            new Reference(EnvironmentExtension::MANAGER_ID),
            new Reference(self::SUITE_ANALYZER_ID),
        ]);

        $references = $this->processor->findAndSortTaggedServices($container, self::SUITES_ANALYZER_ID . '.rule');
        $definition->addMethodCall('setRules', [$references]);

        $container->setDefinition(self::SUITES_ANALYZER_ID . '.inner', $definition);

        $definition = new Definition('GildasQ\BehatAnalysisExtension\EventDispatcher\EventDispatchingSuitesAnalyzer', [
            new Reference(self::SUITES_ANALYZER_ID . '.inner'),
            new Reference(EventDispatcherExtension::DISPATCHER_ID),
        ]);

        $container->setDefinition(self::SUITES_ANALYZER_ID, $definition);
    }

    private function loadController(ContainerBuilder $container)
    {
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Cli\AnalysisController', [
            new Reference(SuiteExtension::REGISTRY_ID),
            new Reference(SpecificationExtension::FINDER_ID),
            new Reference(self::SUITES_ANALYZER_ID)
        ]);
        $definition->addTag(CliExtension::CONTROLLER_TAG, array('priority' => 300));
        $container->setDefinition(CliExtension::CONTROLLER_TAG . '.analysis', $definition);
    }
}

