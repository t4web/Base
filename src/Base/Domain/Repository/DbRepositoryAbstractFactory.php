<?php

namespace Base\Domain\Repository;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class DbRepositoryAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('DbRepository')) == 'DbRepository';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $namespace = strstr($requestedName, 'DbRepository', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $tableGateway = $serviceLocator->get("$moduleName\\{$entityName}\\Db\\Table");
        $DbMapper = $serviceLocator->get("$moduleName\\{$entityName}\\Mapper\\DbMapper");
        $queryBuilder = $serviceLocator->get('Base\Db\QueryBuilder');
        
        return new DbRepository($tableGateway, $DbMapper, $queryBuilder);
    }

}