<?php

declare (strict_types = 1);

namespace GildasQ\BehatAnalysisExtension;

use Behat\Testwork\ServiceContainer\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Testwork\Cli\ServiceContainer\CliExtension;

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
        $definition = new Definition('GildasQ\BehatAnalysisExtension\Cli\AnalysisController');
        $definition->addTag(CliExtension::CONTROLLER_TAG, array('priority' => 300));
        $container->setDefinition(CliExtension::CONTROLLER_TAG . '.analysis', $definition);
    }
}

