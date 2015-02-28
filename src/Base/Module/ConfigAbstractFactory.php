<?php

namespace Base\Module;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * Fix code duplicates like
    'Pages\ModuleConfig' => function(ServiceManager $serviceLocator) {
        return new ModuleConfig(include __DIR__ . '/config/module.config.php');
    },
 */
class ConfigAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('ModuleConfig')) == 'ModuleConfig';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $moduleName = strstr($requestedName, '\ModuleConfig', true);
        
        $pathToConfig = "module/$moduleName/config/module.config.php";
        
        if (!file_exists($pathToConfig)) {
            throw new \RuntimeException("Cannot load config $pathToConfig");
        }
        
        return new ModuleConfig(include $pathToConfig);
    }

}