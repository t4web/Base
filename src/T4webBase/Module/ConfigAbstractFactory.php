<?php

namespace T4webBase\Module;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * Fix code duplicates like
    'Pages\ModuleConfig' => function(ServiceManager $serviceLocator) {
        return new ModuleConfig(include __DIR__ . '/config/module.config.php');
    },
 */
class ConfigAbstractFactory implements AbstractFactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('ModuleConfig')) == 'ModuleConfig';
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @return ModuleConfig
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $moduleName = strstr($requestedName, '\ModuleConfig', true);

        /** @var \Zend\ModuleManager\ModuleManager */
        $moduleManager = $serviceLocator->get('ModuleManager');

        $config = $moduleManager->getModule($moduleName)->getConfig();

        if (empty($config)) {
            throw new \RuntimeException("Cannot load config for module $moduleName");
        }
        
        return new ModuleConfig($config);
    }

}