<?php

namespace T4webBase\Domain\Criteria;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class CriteriaFactoryAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('CriteriaFactory')) == 'CriteriaFactory';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $namespace = strstr($requestedName, 'CriteriaFactory', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $moduleConfig = $serviceLocator->get("$moduleName\ModuleConfig");
        
        return new Factory($moduleName, $entityName, $moduleConfig->getDbDependencies(), $moduleConfig->getCriteries());
    }

}