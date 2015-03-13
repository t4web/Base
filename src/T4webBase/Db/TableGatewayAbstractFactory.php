<?php

namespace T4webBase\Db;

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

        // just for initialize global static adapter
        $serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        return new TableGateway($tableName);
    }

}
