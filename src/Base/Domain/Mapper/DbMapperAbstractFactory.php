<?php

namespace Base\Domain\Mapper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class DbMapperAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('DbMapper')) == 'DbMapper';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        
        $namespace = strstr($requestedName, 'DbMapper', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $moduleConfig = $serviceLocator->get("$moduleName\\ModuleConfig");
        
        return new DbMapper(
            $moduleConfig->getDbTableColumnsAsAttributesMap(strtolower("$moduleName-$entityName")),
            $serviceLocator->get("$moduleName\\$entityName\\Factory\\EntityFactory")
        );
    }

}