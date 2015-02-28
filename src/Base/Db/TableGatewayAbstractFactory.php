<?php

namespace Base\Db;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class TableGatewayAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('TableGateway')) == 'TableGateway';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $namespace = strstr($requestedName, 'TableGateway', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $moduleConfig = $serviceLocator->get("$moduleName\\ModuleConfig");
        
        $tableName = $moduleConfig->getDbTableName(strtolower("$moduleName-$entityName"));
        
        return new TableGateway($tableName);
    }

}
