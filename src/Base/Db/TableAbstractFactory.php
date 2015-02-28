<?php

namespace Base\Db;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class TableAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('Table')) == 'Table';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $namespace = strstr($requestedName, 'Table', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $moduleConfig = $serviceLocator->get("$moduleName\\ModuleConfig");
        
        $tableGateway = $serviceLocator->get("$moduleName\\{$entityName}\\Db\\TableGateway");
        $primaryKey = $moduleConfig->getDbTablePrimaryKey(strtolower("$moduleName-$entityName"));
        
        return new Table($tableGateway, $primaryKey);
    }

}
