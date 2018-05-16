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

final class AnalysisExtension implements Extension
{
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
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Cli\AnalysisController', [
            new Reference(SuiteExtension::REGISTRY_ID),
            new Reference(SpecificationExtension::FINDER_ID),
            new Reference(EnvironmentExtension::MANAGER_ID),
            new Reference(DefinitionExtension::FINDER_ID),
            new Reference(DefinitionExtension::REPOSITORY_ID),
        ]);
        $definition->addTag(CliExtension::CONTROLLER_TAG, array('priority' => 300));
        $container->setDefinition(CliExtension::CONTROLLER_TAG . '.analysis', $definition);
    }
}

